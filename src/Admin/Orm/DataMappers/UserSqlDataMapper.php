<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Orm\DataMappers;

use AbterPhp\Admin\Domain\Entities\User as Entity;
use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Admin\Domain\Entities\UserLanguage;
use Opulence\Orm\DataMappers\SqlDataMapper;
use Opulence\QueryBuilders\MySql\QueryBuilder;
use Opulence\QueryBuilders\MySql\SelectQuery;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class UserSqlDataMapper extends SqlDataMapper implements IUserDataMapper
{
    /**
     * @param Entity $entity
     */
    public function add($entity)
    {
        if (!$entity instanceof Entity) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a User entity.');
        }

        $query = (new QueryBuilder())
            ->insert(
                'users',
                [
                    'username'            => [$entity->getUsername(), \PDO::PARAM_STR],
                    'email'               => [$entity->getEmail(), \PDO::PARAM_STR],
                    'password'            => [$entity->getPassword(), \PDO::PARAM_STR],
                    'user_group_id'       => [$entity->getUserGroup()->getId(), \PDO::PARAM_INT],
                    'user_language_id'    => [$entity->getUserLanguage()->getId(), \PDO::PARAM_INT],
                    'can_login'           => [$entity->canLogin(), \PDO::PARAM_INT],
                    'is_gravatar_allowed' => [$entity->isGravatarAllowed(), \PDO::PARAM_INT],
                ]
            );

        $statement = $this->writeConnection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();

        $entity->setId($this->writeConnection->lastInsertId());
    }

    /**
     * @param Entity $entity
     */
    public function delete($entity)
    {
        if (!$entity instanceof Entity) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a User entity.');
        }

        $query = (new QueryBuilder())
            ->update('users', 'users', ['deleted' => [1, \PDO::PARAM_INT]])
            ->where('id = ?')
            ->addUnnamedPlaceholderValue($entity->getId(), \PDO::PARAM_INT);

        $statement = $this->writeConnection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();
    }

    /**
     * @return Entity[]
     */
    public function getAll(): array
    {
        $query = $this->getBaseQuery();

        $sql = $query->getSql();

        return $this->read($sql, [], self::VALUE_TYPE_ARRAY);
    }

    /**
     * @param int      $limitFrom
     * @param int      $pageSize
     * @param string[] $orders
     * @param array    $conditions
     * @param array    $params
     *
     * @return Entity[]
     */
    public function getPage(int $limitFrom, int $pageSize, array $orders, array $conditions, array $params): array
    {
        $query = $this->getBaseQuery()
            ->limit($pageSize)
            ->offset($limitFrom);

        foreach ($orders as $order) {
            $query->addOrderBy($order);
        }

        foreach ($conditions as $condition) {
            $query->andWhere($condition);
        }

        $replaceCount = 1;

        $sql = $query->getSql();
        $sql = str_replace('SELECT', 'SELECT SQL_CALC_FOUND_ROWS', $sql, $replaceCount);

        return $this->read($sql, $params, self::VALUE_TYPE_ARRAY);
    }

    /**
     * @param int|string $id
     *
     * @return Entity|null
     */
    public function getById($id): ?Entity
    {
        $query = $this->getBaseQuery()->andWhere('users.id = :user_id');

        $parameters = ['user_id' => [$id, \PDO::PARAM_INT]];

        return $this->read($query->getSql(), $parameters, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param string $identifier
     *
     * @return Entity|null
     */
    public function find(string $identifier): ?Entity
    {
        $query = $this->getBaseQuery()->andWhere('(username = :identifier OR email = :identifier)');

        $parameters = ['identifier' => [$identifier, \PDO::PARAM_STR]];

        return $this->read($query->getSql(), $parameters, self::VALUE_TYPE_ENTITY);
    }

    /**
     * @param string $username
     *
     * @return Entity|null
     */
    public function getByUsername(string $username): ?Entity
    {
        $query = $this->getBaseQuery()->andWhere('`username` = :username');

        $parameters = ['username' => [$username, \PDO::PARAM_STR]];

        return $this->read($query->getSql(), $parameters, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param string $email
     *
     * @return Entity|null
     */
    public function getByEmail(string $email): ?Entity
    {
        $query = $this->getBaseQuery()->andWhere('email = :email');

        $parameters = ['email' => [$email, \PDO::PARAM_STR]];

        return $this->read($query->getSql(), $parameters, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param Entity $entity
     */
    public function update($entity)
    {
        if (!$entity instanceof Entity) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a User entity.');
        }

        $query = (new QueryBuilder())
            ->update(
                'users',
                'users',
                [
                    'username'            => [$entity->getUsername(), \PDO::PARAM_STR],
                    'email'               => [$entity->getEmail(), \PDO::PARAM_STR],
                    'password'            => [$entity->getPassword(), \PDO::PARAM_STR],
                    'user_group_id'       => [$entity->getUserGroup()->getId(), \PDO::PARAM_INT],
                    'user_language_id'    => [$entity->getUserLanguage()->getId(), \PDO::PARAM_INT],
                    'can_login'           => [$entity->canLogin(), \PDO::PARAM_INT],
                    'is_gravatar_allowed' => [$entity->isGravatarAllowed(), \PDO::PARAM_INT],
                ]
            )
            ->where('id = ?')
            ->addUnnamedPlaceholderValue($entity->getId(), \PDO::PARAM_INT);

        $statement = $this->writeConnection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();
    }

    /**
     * @param array $data
     *
     * @return Entity
     */
    protected function loadEntity(array $data): Entity
    {
        $userGroup = new UserGroup(
            (int)$data['user_group_id'],
            $data['user_group_identifier'],
            $data['user_group_name']
        );

        $userLanguage = new UserLanguage(
            (int)$data['user_language_id'],
            $data['user_language_identifier'],
            ''
        );

        return new Entity(
            (int)$data['id'],
            $data['username'],
            $data['email'],
            $data['password'],
            $userGroup,
            $userLanguage,
            (bool)$data['can_login'],
            (bool)$data['is_gravatar_allowed']
        );
    }

    /**
     * @return SelectQuery
     */
    private function getBaseQuery(): SelectQuery
    {
        /** @var SelectQuery $query */
        $query = (new QueryBuilder())
            ->select(
                'users.id',
                'users.username',
                'users.email',
                'users.password',
                'users.user_group_id',
                'user_groups.identifier AS user_group_identifier',
                'user_groups.name AS user_group_name',
                'users.user_language_id',
                'user_languages.identifier AS user_language_identifier',
                'users.can_login',
                'users.is_gravatar_allowed'
            )
            ->from('users')
            ->innerJoin(
                'user_groups',
                'user_groups',
                'user_groups.id = users.user_group_id AND user_groups.deleted = 0'
            )
            ->innerJoin(
                'user_languages',
                'user_languages',
                'user_languages.id = users.user_language_id AND user_languages.deleted = 0'
            )
            ->where('users.deleted = 0');

        return $query;
    }
}
