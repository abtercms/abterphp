<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Orm;

use AbterPhp\Admin\Domain\Entities\AdminResource;
use AbterPhp\Admin\Domain\Entities\ApiClient as Entity;
use AbterPhp\Framework\Orm\GridRepo;
use AbterPhp\Framework\Orm\IdGeneratorUserTrait;
use InvalidArgumentException;
use Opulence\Orm\IEntity;
use QB\Generic\Expr\Expr;
use QB\Generic\Statement\ISelect;
use QB\MySQL\QueryBuilder\QueryBuilder;

class ApiClientRepo extends GridRepo
{
    use IdGeneratorUserTrait;

    /** @var QueryBuilder */
    protected $queryBuilder;

    protected string $tableName = 'api_clients';

    protected ?string $deletedAtColumn = self::COLUMN_DELETED_AT;

    /**
     * @param IEntity $entity
     */
    public function add(IEntity $entity)
    {
        assert($entity instanceof Entity, new InvalidArgumentException());

        parent::add($entity);
    }

    /**
     * @param Entity $entity
     */
    public function delete(IEntity $entity)
    {
        assert($entity instanceof Entity, new InvalidArgumentException());

        parent::delete($entity);
    }

    /**
     * @param Entity $entity
     */
    public function update(IEntity $entity)
    {
        assert($entity instanceof Entity, new InvalidArgumentException());

        parent::update($entity);

        $this->deleteAdminResources($entity);
        $this->addAdminResources($entity);
    }

    /**
     * @param Entity $entity
     */
    protected function deleteAdminResources(Entity $entity)
    {
        $delete = $this->queryBuilder->delete()
            ->from('api_clients_admin_resources')
            ->where(new Expr('api_client_id = ?', [$entity->getId()]));

        $this->writer->execute($delete);
    }

    /**
     * @param Entity $entity
     */
    protected function addAdminResources(Entity $entity)
    {
        $idGenerator = $this->getIdGenerator();

        $insert = $this->queryBuilder->insert()
            ->into('api_clients_admin_resources')
            ->columns('id', 'api_client_id', 'admin_resource_id');

        foreach ($entity->getAdminResources() as $adminResource) {
            $insert->values(
                new Expr('?', [$idGenerator->generate($entity)]),
                new Expr('?', [$entity->getId()]),
                new Expr('?', [$adminResource->getId()])
            );
        }

        $this->writer->execute($insert);
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

    /**
     * @return array<string,string>
     */
    public function getDefaultSorting(): array
    {
        return [
            'user_id'     => ISelect::DIRECTION_ASC,
            'description' => ISelect::DIRECTION_ASC,
        ];
    }
}
