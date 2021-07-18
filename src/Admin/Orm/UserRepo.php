<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Orm;

use AbterPhp\Admin\Domain\Entities\User as Entity;
use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Admin\Domain\Entities\UserLanguage;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\Orm\GridRepo;
use AbterPhp\Framework\Orm\IdGeneratorUserTrait;
use InvalidArgumentException;
use QB\Generic\Clause\Table;
use QB\Generic\Expr\Expr;
use QB\Generic\Statement\ISelect;
use QB\MySQL\QueryBuilder\QueryBuilder;
use QB\MySQL\Statement\Select;

class UserRepo extends GridRepo
{
    use IdGeneratorUserTrait;

    protected string $tableName = 'users';

    protected ?string $deletedAtColumn = self::COLUMN_DELETED_AT;

    /** @var QueryBuilder */
    protected $queryBuilder;

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
     * @param string $clientId
     *
     * @return Entity|null
     */
    public function getByClientId(string $clientId): ?Entity
    {
        $entity = parent::getOne(['client_id' => $clientId]);

        assert($entity === null || $entity instanceof Entity);

        return $entity;
    }

    /**
     * @param string $username
     *
     * @return Entity|null
     */
    public function getByUsername(string $username): ?Entity
    {
        $entity = parent::getOne(['username' => $username]);

        assert($entity === null || $entity instanceof Entity);

        return $entity;
    }

    /**
     * @param string $email
     *
     * @return Entity|null
     */
    public function getByEmail(string $email): ?Entity
    {
        $entity = parent::getOne(['email' => $email]);

        assert($entity === null || $entity instanceof Entity);

        return $entity;
    }

    /**
     * @param string $identifier
     *
     * @return Entity|null
     */
    public function find(string $identifier): ?Entity
    {
        $select = $this->getBaseQuery()
            ->where($this->deletedAtColumn . ' IS NULL')
            ->where(new Expr('(username = ? OR email = ?)', [$identifier, $identifier]))
            ->limit(1);

        $row = $this->writer->fetch($select);
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
        if (empty($row['user_group_ids'])) {
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

        return $this->queryBuilder->select()
            ->from($this->tableName)
            ->columns(...$columns)
            ->innerJoin(new Table('user_languages', 'ul'), 'users.language_id = ul.id')
            ->leftJoin(new Table('users_user_groups', 'usg'), 'users.user_group_id = usg.id')
            ->leftJoin(new Table('user_groups', 'ug'), 'usg.group_id = ug.id')
            ->groupBy('users.id')
            ->where('deleted_at IS NOT NULL');
    }

    /**
     * @return array<string,string>
     */
    public function getDefaultSorting(): array
    {
        return [
            'users.username' => ISelect::DIRECTION_ASC,
        ];
    }
}
