<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Orm;

use AbterPhp\Admin\Domain\Entities\LoginAttempt as Entity;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\Orm\Repository;
use InvalidArgumentException;

class LoginAttemptRepo extends Repository
{
    protected string $tableName = 'login_attempts';

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
     * @param array $row
     *
     * @return Entity
     */
    public function createEntity(array $row): Entity
    {
        return new Entity(
            $row['id'],
            $row['ip_hash'],
            $row['username'],
            $row['ip_address']
        );
    }
}
