<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Orm\DataMappers;

use AbterPhp\Admin\Domain\Entities\User as Entity;
use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Admin\Domain\Entities\UserLanguage;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\Orm\DataMappers\SqlDataMapper;
use Medoo\Medoo;
use Opulence\Orm\OrmException;
use Opulence\QueryBuilders\MySql\QueryBuilder;
use Opulence\QueryBuilders\MySql\SelectQuery;

/** @phan-file-suppress PhanTypeMismatchArgument */
class UserSqlDataMapper extends SqlDataMapper implements IUserDataMapper
{
    use IdGeneratorUserTrait;

    /**
     * @param IStringerEntity $entity
     */
    public function add($entity)
    {
        assert($entity instanceof Entity, new \InvalidArgumentException());

        $this->connection->withRead(
            function () use ($entity) {
                parent::add($entity);

                $this->addUserGroups($entity);
            }
        );
    }

    /**
     * @param IStringerEntity $entity
     */
    public function delete($entity)
    {
        assert($entity instanceof Entity, new \InvalidArgumentException());

        $this->connection->withRead(
            function () use ($entity) {
                $this->deleteUserGroups($entity);

                $data = [
                    'deleted_at' => Medoo::raw('NOW()'),
                    'username'   => Medoo::raw('LEFT(MD5(RAND()), 8)'),
                    'email'      => Medoo::raw('CONCAT(username, "@example.com")'),
                    'password'   => [''],
                ];

                $this->connection->update('users', $data, $this->getWhereByEntity($entity));
            }
        );
    }

    /**
     * @return Entity[]
     */
    public function getAll(): array
    {
        return parent::getAll();
    }

    /**
     * @param int      $limitFrom
     * @param int      $pageSize
     * @param string[] $orders
     * @param array    $filters
     * @param array    $params
     *
     * @return Entity[]
     */
    public function getPage(int $limitFrom, int $pageSize, array $orders, array $filters, array $params): array
    {
        parent::getPage($limitFrom, $pageSize, $orders, $filters, $params);
    }

    /**
     * @param int|string $id
     *
     * @return Entity|null
     * @throws OrmException
     */
    public function getById($id): ?Entity
    {
        return parent::getById($id);
    }

    /**
     * @param string $identifier
     *
     * @return Entity|null
     */
    public function find(string $identifier): ?Entity
    {
        return parent::getOne(['OR' => ['username' => $identifier, 'email' => $identifier]]);
    }

    /**
     * @param string $clientId
     *
     * @return Entity|null
     */
    public function getByClientId(string $clientId): ?Entity
    {
        $query = $this->getBaseQuery()
            ->innerJoin(
                'api_clients',
                'ac',
                'ac.user_id = users.id AND ac.deleted_at IS NULL'
            )
            ->andWhere('ac.id = :client_id');

        $sql    = $query->getSql();
        $params = [
            'client_id' => [$clientId, \PDO::PARAM_STR],
        ];

        return $this->read($sql, $params, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param string $username
     *
     * @return Entity|null
     */
    public function getByUsername(string $username): ?Entity
    {
        return $this->getOne(['username' => $username]);
    }

    /**
     * @param string $email
     *
     * @return Entity|null
     */
    public function getByEmail(string $email): ?Entity
    {
        return $this->getOne(['email' => $email]);
    }

    /**
     * @param IStringerEntity $entity
     */
    public function update($entity)
    {
        assert($entity instanceof Entity, new \InvalidArgumentException());

        $this->connection->withRead(
            function () use ($entity) {
                $this->deleteUserGroups($entity);
                $this->addUserGroups($entity);
            }
        );
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
     * @param array $data
     *
     * @return UserGroup[]
     */
    protected function createUserGroups(array $data): array
    {
        if (empty($data['user_group_ids'])) {
            return [];
        }

        $ids         = explode(',', $data['user_group_ids']);
        $identifiers = explode(',', $data['user_group_identifiers']);
        $names       = explode(',', $data['user_group_names']);

        $userGroups = [];
        foreach ($ids as $idx => $userGroupId) {
            $userGroups[] = new UserGroup($userGroupId, $identifiers[$idx], $names[$idx]);
        }

        return $userGroups;
    }

    /**
     * @return SelectQuery
     */
    private function getBaseQuery(): SelectQuery
    {

        return (new QueryBuilder())
            ->select(
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
            )
            ->from('users')
            ->innerJoin(
                'user_languages',
                'ul',
                'ul.id = users.user_language_id AND ul.deleted_at IS NULL'
            )
            ->leftJoin('users_user_groups', 'uug', 'uug.user_id = users.id AND uug.deleted_at IS NULL')
            ->leftJoin('user_groups', 'ug', 'ug.id = uug.user_group_id AND ug.deleted_at IS NULL')
            ->groupBy('users.id')
            ->where('users.deleted_at IS NULL');
    }

    /**
     * @param Entity $entity
     */
    protected function deleteUserGroups(Entity $entity)
    {
        $this->connection->delete('users_user_groups', ['user_id' => $entity->getId()]);
    }

    /**
     * @param Entity $entity
     */
    protected function addUserGroups(Entity $entity)
    {
        $idGenerator = $this->getIdGenerator();

        $data = [];
        foreach ($entity->getUserGroups() as $userGroup) {
            $data[] = [
                'id'            => [$idGenerator->generate($entity), \PDO::PARAM_STR],
                'user_id'       => [$entity->getId(), \PDO::PARAM_STR],
                'user_group_id' => [$userGroup->getId(), \PDO::PARAM_STR],
            ];
        }

        $this->connection->insert('users_user_groups', ...$data);
    }
}
