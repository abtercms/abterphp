<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Orm;

use AbterPhp\Admin\Domain\Entities\Token as Entity;
use AbterPhp\Framework\Orm\Repository;
use DateTimeImmutable;
use Exception;
use InvalidArgumentException;
use Opulence\Orm\IEntity;

class TokenRepo extends Repository
{
    protected string $tableName = 'tokens';

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
     * @param string $clientId
     *
     * @return Entity|null
     */
    public function getByClientId(string $clientId): ?Entity
    {
        return $this->getOne(['api_client_id' => $clientId]);
    }

    /**
     * @param array $row
     *
     * @return Entity
     * @throws Exception
     */
    public function createEntity(array $row): Entity
    {
        $expiresAt = new DateTimeImmutable($row['expires_at']);
        $revokedAt = null;
        if (null !== $row['revoked_at']) {
            $revokedAt = new DateTimeImmutable($row['revoked_at']);
        }

        return new Entity(
            $row['id'],
            $row['api_client_id'],
            $expiresAt,
            $revokedAt
        );
    }
}
