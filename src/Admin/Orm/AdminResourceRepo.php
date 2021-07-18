<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Orm;

use AbterPhp\Admin\Domain\Entities\AdminResource as Entity;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\Orm\Repository;
use InvalidArgumentException;
use QB\Generic\Expr\Expr;
use QB\MySQL\QueryBuilder\QueryBuilder;

class AdminResourceRepo extends Repository
{
    /** @var QueryBuilder */
    protected $queryBuilder;

    protected string $tableName = 'admin_resources';

    protected ?string $deletedAtColumn = self::COLUMN_DELETED_AT;

    /**
     * @suppress PhanParamSignatureMismatch
     *
     * @param Entity $entity
     */
    public function add(IStringerEntity $entity)
    {
        assert($entity instanceof Entity, new InvalidArgumentException());

        parent::add($entity);
    }

    /**
     * @suppress PhanParamSignatureMismatch
     *
     * @param Entity $entity
     */
    public function update(IStringerEntity $entity)
    {
        assert($entity instanceof Entity, new InvalidArgumentException());

        parent::update($entity);
    }

    /**
     * @suppress PhanParamSignatureMismatch
     *
     * @param Entity $entity
     */
    public function delete(IStringerEntity $entity)
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
     * @param string $userId
     *
     * @return Entity[]
     */
    public function getByUserId(string $userId): array
    {
        $select = $this->queryBuilder->select()
            ->from($this->tableName)
            ->innerJoin(
                'user_groups_admin_resources',
                'user_groups_admin_resources.admin_resource_id = admin_resources.id'
            )
            ->innerJoin('user_groups', 'user_groups.id = user_groups_admin_resources.user_group_id')
            ->innerJoin('users_user_groups', 'users_user_groups.user_group_id = user_groups.id')
            ->where(new Expr('users_user_groups.user_id = ?', [$userId]))
            ->groupBy('admin_resources.id');

        $rows = $this->writer->fetchAll($select);

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
