<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Orm;

use AbterPhp\Admin\Domain\Entities\AdminResource;
use AbterPhp\Admin\Domain\Entities\ApiClient as Entity;
use AbterPhp\Framework\Orm\IdGeneratorUserTrait;
use AbterPhp\Framework\Orm\IGridRepo;
use AbterPhp\Framework\Orm\Repository;
use Opulence\Orm\IEntity;

class ApiClientRepo extends Repository implements IGridRepo
{
    use IdGeneratorUserTrait;

    protected string $tableName = 'api_clients';

    protected ?string $deletedAtColumn = self::COLUMN_DELETED_AT;

    /**
     * @param IEntity $entity
     */
    public function add(IEntity $entity)
    {
        assert($entity instanceof Entity, new \InvalidArgumentException());

        parent::add($entity);
    }

    /**
     * @param Entity $entity
     */
    public function delete(IEntity $entity)
    {
        assert($entity instanceof Entity, new \InvalidArgumentException());

        parent::delete($entity);
    }

    /**
     * @param Entity $entity
     */
    public function update(IEntity $entity)
    {
        assert($entity instanceof Entity, new \InvalidArgumentException());

        $this->writer->withWrite(
            function () use ($entity) {
                try {
                    parent::update($entity);

                    $this->deleteAdminResources($entity);
                    $this->addAdminResources($entity);
                } catch (\Exception $e) {
                    return false;
                }

                return true;
            }
        );
    }

    /**
     * @param Entity $entity
     */
    protected function deleteAdminResources(Entity $entity)
    {
        $delete = $this->queryBuilder->delete('api_clients_admin_resources')
            ->where()
            ->equals('api_client_id', $entity->getId())
            ->end();

        $this->writer->exec($delete->getSql());
    }

    /**
     * @param Entity $entity
     */
    protected function addAdminResources(Entity $entity)
    {
        $idGenerator = $this->getIdGenerator();

        $values = [
            'id'                => ':id',
            'api_client_id'     => ':api_client_id',
            'admin_resource_id' => ':admin_resource_id',
        ];

        $insert = $this->queryBuilder->insert('api_clients_admin_resources', $values);

        $stmt = $this->writer->prepare($insert->getSql());

        foreach ($entity->getAdminResources() as $adminResource) {
            $stmt->execute(
                [
                    'id'                => $idGenerator->generate($entity),
                    'api_client_id'     => $entity->getId(),
                    'admin_resource_id' => $adminResource->getId(),
                ]
            );
        }
    }

    /**
     * @param array $row
     *
     * @return Entity
     */
    public function createEntity(array $row): Entity
    {
        $adminResources = $this->createAdminResources($row);

        return new Entity(
            $row['id'],
            $row['user_id'],
            $row['description'],
            $row['secret'],
            $adminResources
        );
    }

    /**
     * @param array $row
     *
     * @return array
     */
    protected function createAdminResources(array $row): array
    {
        if (empty($row['admin_resource_ids'])) {
            return [];
        }

        $adminResourceIds         = explode(',', $row['admin_resource_ids']);
        $adminResourceIdentifiers = explode(',', $row['admin_resource_identifiers']);

        $adminResources = [];
        foreach ($adminResourceIds as $idx => $adminResourceId) {
            $adminResources[] = new AdminResource($adminResourceId, $adminResourceIdentifiers[$idx]);
        }

        return $adminResources;
    }
}
