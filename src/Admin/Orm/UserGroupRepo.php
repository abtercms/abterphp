<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Orm;

use AbterPhp\Admin\Domain\Entities\AdminResource;
use AbterPhp\Admin\Domain\Entities\UserGroup as Entity;
use AbterPhp\Framework\Orm\GridRepo;
use AbterPhp\Framework\Orm\IdGeneratorUserTrait;
use InvalidArgumentException;
use Opulence\Orm\IEntity;
use QB\Generic\Expr\Expr;
use QB\MySQL\QueryBuilder\QueryBuilder;

class UserGroupRepo extends GridRepo
{
    use IdGeneratorUserTrait;

    protected string $tableName = 'user_groups';

    protected ?string $deletedAtColumn = self::COLUMN_DELETED_AT;

    /** @var QueryBuilder */
    protected $queryBuilder;

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
    public function update(IEntity $entity)
    {
        assert($entity instanceof Entity, new InvalidArgumentException());

        parent::update($entity);
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
     * @param string $identifier
     *
     * @return Entity|null
     */
    public function getByIdentifier(string $identifier): ?Entity
    {
        $entity = $this->getOne(['identifier' => $identifier]);

        assert($entity === null || $entity instanceof Entity);

        return $entity;
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
        $delete = $this->queryBuilder->delete()
            ->from('user_groups_admin_resources')
            ->where(new Expr('user_group_id = ?', [$entity->getId()]));

        $this->writer->execute($delete);
    }

    /**
     * @param Entity $entity
     */
    protected function addAdminResources(Entity $entity)
    {
        $idGenerator = $this->getIdGenerator();

        $insert = $this->queryBuilder->insert()
            ->into('user_groups_admin_resources')
            ->columns('id', 'api_client_id', 'admin_resource_id');

        foreach ($entity->getAdminResources() as $adminResource) {
            $insert->values(
                new Expr('?', [$idGenerator->generate($entity)]),
                new Expr('?', [$entity->getId()]),
                new Expr('?', [$adminResource->getId()]),
            );
        }

        $this->writer->execute($insert);
    }

    /**
     * @return array<string,string>
     */
    public function getDefaultSorting(): array
    {
        return [];
    }
}
