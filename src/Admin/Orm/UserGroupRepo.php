<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Orm;

use AbterPhp\Admin\Domain\Entities\AdminResource;
use AbterPhp\Admin\Domain\Entities\UserGroup as Entity;
use AbterPhp\Framework\Orm\IdGeneratorUserTrait;
use AbterPhp\Framework\Orm\IGridRepo;
use AbterPhp\Framework\Orm\Repository;
use Opulence\Orm\IEntity;

class UserGroupRepo extends Repository implements IGridRepo
{
    use IdGeneratorUserTrait;

    protected string $tableName = 'user_groups';

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
    public function update(IEntity $entity)
    {
        assert($entity instanceof Entity, new \InvalidArgumentException());

        parent::update($entity);
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
     * @param string $identifier
     *
     * @return Entity|null
     */
    public function getByIdentifier(string $identifier): ?Entity
    {
        return $this->getOne(['identifier' => $identifier]);
    }

    /**
     * @param array $row
     *
     * @return Entity
     */
    public function createEntity(array $row): Entity
    {
        $adminResources = $this->getAdminResources($row);

        return new Entity(
            $row['id'],
            $row['identifier'],
            $row['name'],
            $adminResources
        );
    }

    /**
     * @param array $row
     *
     * @return AdminResource[]
     */
    private function getAdminResources(array $row): array
    {
        if (empty($row['admin_resource_ids'])) {
            return [];
        }

        $adminResources = [];
        foreach (explode(',', $row['admin_resource_ids']) as $id) {
            $adminResources[] = new AdminResource($id, '');
        }

        return $adminResources;
    }

    /**
     * @param Entity $entity
     */
    protected function deleteAdminResources(Entity $entity)
    {
        $delete = $this->queryBuilder->delete('user_groups_admin_resources')
            ->where()
            ->equals('user_group_id', $entity->getId())
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

        $insert = $this->queryBuilder->insert('user_groups_admin_resources', $values);

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
}
