<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Orm;

use AbterPhp\Admin\Domain\Entities\User as Entity;
use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Admin\Domain\Entities\UserLanguage;
use AbterPhp\Framework\Orm\IdGeneratorUserTrait;
use AbterPhp\Framework\Orm\IGridRepo;
use AbterPhp\Framework\Orm\Repository;
use NilPortugues\Sql\QueryBuilder\Manipulation\Select;
use NilPortugues\Sql\QueryBuilder\Syntax\Where;
use Opulence\Orm\IEntity;

class UserRepo extends Repository implements IGridRepo
{
    use IdGeneratorUserTrait;

    protected string $tableName = 'users';

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
     * @param string $clientId
     *
     * @return Entity|null
     */
    public function getByClientId(string $clientId): ?Entity
    {
    }

    /**
     * @param string $username
     *
     * @return Entity|null
     */
    public function getByUsername(string $username): ?Entity
    {
        return parent::getOne(['username' => $username]);
    }

    /**
     * @param string $email
     *
     * @return Entity|null
     */
    public function getByEmail(string $email): ?Entity
    {
        return parent::getOne(['email' => $email]);
    }

    /**
     * @param string $identifier
     *
     * @return Entity|null
     */
    public function find(string $identifier): ?Entity
    {
        $select = $this->getBaseQuery()
            ->where()
            ->equals($this->deletedAtColumn, null)
            ->subWhere()
            ->equals('username', $identifier)
            ->equals('email', $identifier)
            ->end()
            ->limit(0, 1);

        $row = $this->writer->fetch($select->getSql());

        if (empty($row)) {
            return null;
        }

        return $this->createEntity($row);
    }

    /**
     * @param array $row
     *
     * @return Entity
     */
    public function createEntity(array $row): Entity
    {
        $userLanguage = new UserLanguage(
            $row['user_language_id'],
            $row['user_language_identifier'],
            ''
        );
        $userGroups   = $this->createUserGroups($row);

        return new Entity(
            $row['id'],
            $row['username'],
            $row['email'],
            $row['password'],
            (bool)$row['can_login'],
            (bool)$row['is_gravatar_allowed'],
            $userLanguage,
            $userGroups
        );
    }

    /**
     * @param array $row
     *
     * @return UserGroup[]
     */
    protected function createUserGroups(array $row): array
    {
        if (empty($data['user_group_ids'])) {
            return [];
        }

        $ids         = explode(',', $row['user_group_ids']);
        $identifiers = explode(',', $row['user_group_identifiers']);
        $names       = explode(',', $row['user_group_names']);

        $userGroups = [];
        foreach ($ids as $idx => $userGroupId) {
            $userGroups[] = new UserGroup($userGroupId, $identifiers[$idx], $names[$idx]);
        }

        return $userGroups;
    }

    /**
     * @return Select
     */
    protected function getBaseQuery(): Select
    {
        $columns = [
            'users.id',
            'users.username',
            'users.email',
            'users.password',
            'users.user_language_id',
            'ul.identifier AS user_language_identifier',
            'users.can_login',
            'users.is_gravatar_allowed',
            'GROUP_CONCAT(ug.id) AS user_group_ids',
            'GROUP_CONCAT(ug.identifier) AS user_group_identifiers',
            'GROUP_CONCAT(ug.name) AS user_group_names'
        ];

        return $this->queryBuilder->select($this->tableName, $columns)
            ->innerJoin('user_languages', )
            ->leftJoin('users_user_groups')
            ->leftJoin('user_groups')
            ->groupBy('id')
            ->where()
            ->isNotNull('deleted_at');
    }

}
