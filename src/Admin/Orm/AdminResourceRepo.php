<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Orm;

use AbterPhp\Admin\Domain\Entities\AdminResource as Entity;
use AbterPhp\Framework\Orm\Repository;
use Opulence\Orm\IEntity;

class AdminResourceRepo extends Repository
{
    protected string $tableName = 'admin_resources';

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
        $this->getOne(['identifier' => $identifier]);
    }

    /**
     * @param string $userId
     *
     * @return Entity[]
     */
    public function getByUserId(string $userId): array
    {
        $sql = sprintf(
            'SELECT %s FROM %s INNER JOIN %s INNER JOIN %s INNER JOIN %s WHERE %s GROUP BY %s',
            $this->getColumnsStr(),
            $this->tableName,
            'user_groups_admin_resources ON user_groups_admin_resources.admin_resource_id = admin_resources.id',
            'user_groups ON user_groups.id = user_groups_admin_resources.user_group_id',
            'users_user_groups ON users_user_groups.user_group_id = user_groups.id',
            'users_user_groups.user_id = :user_id',
            'admin_resources.id'
        );

        $stmt = $this->writer->prepare($sql);

        $stmt->execute(['user_id' => $userId]);

        $rows = $stmt->fetchAll();

        return $this->createCollection($rows);
    }

    /**
     * @param array $row
     *
     * @return Entity
     */
    public function createEntity(array $row): Entity
    {
        return new Entity(
            $row['id'],
            $row['identifier']
        );
    }
}
