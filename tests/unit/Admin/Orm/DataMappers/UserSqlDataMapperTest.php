<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Orm\DataMapper;

use AbterPhp\Admin\Domain\Entities\User;
use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Admin\Domain\Entities\UserLanguage;
use AbterPhp\Admin\Orm\DataMappers\UserSqlDataMapper;
use AbterPhp\Framework\Orm\DataMapper\SqlDataMapperTest;
use Opulence\Databases\Adapters\Pdo\Connection as Connection;

class UserSqlDataMapperTest extends SqlDataMapperTest
{
    /** @var UserSqlDataMapper */
    protected $sut;

    public function setUp()
    {
        $this->connection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->setMethods(['prepare', 'read', 'lastInsertId'])
            ->getMock();

        $this->sut = new UserSqlDataMapper($this->connection, $this->connection);
    }

    public function testAddWithoutRelated()
    {
        $nextId            = '123';
        $username          = 'foo';
        $email             = 'foo@example.com';
        $password          = '';
        $userLanguage      = new UserLanguage(72, 'baz', 'Baz');
        $canLogin          = true;
        $isGravatarAllowed = true;

        $sql    = 'INSERT INTO users (username, email, password, user_language_id, can_login, is_gravatar_allowed) VALUES (?, ?, ?, ?, ?, ?)'; // phpcs:ignore
        $values = [
            [$username, \PDO::PARAM_STR],
            [$email, \PDO::PARAM_STR],
            [$password, \PDO::PARAM_STR],
            [$userLanguage->getId(), \PDO::PARAM_INT],
            [$canLogin, \PDO::PARAM_INT],
            [$isGravatarAllowed, \PDO::PARAM_INT],
        ];
        $this->prepare($sql, $this->createWriteStatement($values));

        $this->lastInsertId($nextId);

        $entity = new User(0, $username, $email, $password, $canLogin, $isGravatarAllowed, $userLanguage);
        $this->sut->add($entity);

        $this->assertSame($nextId, $entity->getId());
    }

    public function testAddWithRelated()
    {
        $nextId            = '123';
        $username          = 'foo';
        $email             = 'foo@example.com';
        $password          = '';
        $userLanguage      = new UserLanguage(72, 'baz', 'Baz');
        $canLogin          = true;
        $isGravatarAllowed = true;
        $userGroups        = [new UserGroup(38, 'ug-38', 'UG 38'), new UserGroup(51, 'ug-51', 'UG 51')];

        $sql0   = 'INSERT INTO users (username, email, password, user_language_id, can_login, is_gravatar_allowed) VALUES (?, ?, ?, ?, ?, ?)'; // phpcs:ignore
        $values = [
            [$username, \PDO::PARAM_STR],
            [$email, \PDO::PARAM_STR],
            [$password, \PDO::PARAM_STR],
            [$userLanguage->getId(), \PDO::PARAM_INT],
            [$canLogin, \PDO::PARAM_INT],
            [$isGravatarAllowed, \PDO::PARAM_INT],
        ];
        $this->prepare($sql0, $this->createWriteStatement($values), 0);

        $this->lastInsertId($nextId);

        $sql1    = 'INSERT INTO users_user_groups (user_id, user_group_id) VALUES (?, ?)'; // phpcs:ignore
        $values1 = [[$nextId, \PDO::PARAM_INT], [$userGroups[0]->getId(), \PDO::PARAM_INT]];
        $this->prepare($sql1, $this->createWriteStatement($values1), 2);

        $sql2    = 'INSERT INTO users_user_groups (user_id, user_group_id) VALUES (?, ?)'; // phpcs:ignore
        $values2 = [[$nextId, \PDO::PARAM_INT], [$userGroups[1]->getId(), \PDO::PARAM_INT]];
        $this->prepare($sql2, $this->createWriteStatement($values2), 3);

        $entity = new User(0, $username, $email, $password, $canLogin, $isGravatarAllowed, $userLanguage, $userGroups);
        $this->sut->add($entity);

        $this->assertSame($nextId, $entity->getId());
    }

    public function testDelete()
    {
        $id                = 123;
        $username          = 'foo';
        $email             = 'foo@example.com';
        $password          = '';
        $userLanguage      = new UserLanguage(72, 'baz', 'Baz');
        $canLogin          = true;
        $isGravatarAllowed = true;

        $sql0    = 'DELETE FROM users_user_groups WHERE (user_id = ?)'; // phpcs:ignore
        $values0 = [[$id, \PDO::PARAM_INT]];
        $this->prepare($sql0, $this->createWriteStatement($values0), 0);

        $sql1   = 'UPDATE users AS users SET deleted = ?, email = ?, username = ?, password = ? WHERE (id = ?)'; // phpcs:ignore
        $this->prepare($sql1, $this->createWriteStatementWithAny(), 1);

        $entity = new User($id, $username, $email, $password, $canLogin, $isGravatarAllowed, $userLanguage);
        $this->sut->delete($entity);
    }

    public function testGetAll()
    {
        $id                = 123;
        $username          = 'foo';
        $email             = 'foo@example.com';
        $password          = '';
        $userLanguage      = new UserLanguage(72, 'baz', 'Baz');
        $canLogin          = true;
        $isGravatarAllowed = true;

        $sql          = 'SELECT users.id, users.username, users.email, users.password, users.user_language_id, ul.identifier AS user_language_identifier, users.can_login, users.is_gravatar_allowed, GROUP_CONCAT(ug.id) AS user_group_ids, GROUP_CONCAT(ug.identifier) AS user_group_identifiers, GROUP_CONCAT(ug.name) AS user_group_names FROM users INNER JOIN user_languages AS ul ON ul.id = users.user_language_id AND ul.deleted = 0 LEFT JOIN users_user_groups AS uug ON uug.user_id = users.id AND uug.deleted = 0 LEFT JOIN user_groups AS ug ON ug.id = uug.user_group_id AND ug.deleted = 0 WHERE (users.deleted = 0)'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'                       => $id,
                'username'                 => $username,
                'email'                    => $email,
                'password'                 => $password,
                'user_language_id'         => $userLanguage->getId(),
                'user_language_identifier' => $userLanguage->getIdentifier(),
                'can_login'                => $canLogin,
                'is_gravatar_allowed'      => $isGravatarAllowed,
            ],
        ];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getAll();

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetById()
    {
        $id                = 123;
        $username          = 'foo';
        $email             = 'foo@example.com';
        $password          = '';
        $userLanguage      = new UserLanguage(72, 'baz', 'Baz');
        $canLogin          = true;
        $isGravatarAllowed = true;

        $sql          = 'SELECT users.id, users.username, users.email, users.password, users.user_language_id, ul.identifier AS user_language_identifier, users.can_login, users.is_gravatar_allowed, GROUP_CONCAT(ug.id) AS user_group_ids, GROUP_CONCAT(ug.identifier) AS user_group_identifiers, GROUP_CONCAT(ug.name) AS user_group_names FROM users INNER JOIN user_languages AS ul ON ul.id = users.user_language_id AND ul.deleted = 0 LEFT JOIN users_user_groups AS uug ON uug.user_id = users.id AND uug.deleted = 0 LEFT JOIN user_groups AS ug ON ug.id = uug.user_group_id AND ug.deleted = 0 WHERE (users.deleted = 0) AND (users.id = :user_id)'; // phpcs:ignore
        $values       = ['user_id' => [$id, \PDO::PARAM_INT]];
        $expectedData = [
            [
                'id'                       => $id,
                'username'                 => $username,
                'email'                    => $email,
                'password'                 => $password,
                'user_language_id'         => $userLanguage->getId(),
                'user_language_identifier' => $userLanguage->getIdentifier(),
                'can_login'                => $canLogin,
                'is_gravatar_allowed'      => $isGravatarAllowed,
            ],
        ];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getById($id);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testGetByUsername()
    {
        $id                = 123;
        $username          = 'foo';
        $email             = 'foo@example.com';
        $password          = '';
        $userLanguage      = new UserLanguage(72, 'baz', 'Baz');
        $canLogin          = true;
        $isGravatarAllowed = true;

        $sql          = 'SELECT users.id, users.username, users.email, users.password, users.user_language_id, ul.identifier AS user_language_identifier, users.can_login, users.is_gravatar_allowed, GROUP_CONCAT(ug.id) AS user_group_ids, GROUP_CONCAT(ug.identifier) AS user_group_identifiers, GROUP_CONCAT(ug.name) AS user_group_names FROM users INNER JOIN user_languages AS ul ON ul.id = users.user_language_id AND ul.deleted = 0 LEFT JOIN users_user_groups AS uug ON uug.user_id = users.id AND uug.deleted = 0 LEFT JOIN user_groups AS ug ON ug.id = uug.user_group_id AND ug.deleted = 0 WHERE (users.deleted = 0) AND (`username` = :username)'; // phpcs:ignore
        $values       = ['username' => [$username, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'                       => $id,
                'username'                 => $username,
                'email'                    => $email,
                'password'                 => $password,
                'user_language_id'         => $userLanguage->getId(),
                'user_language_identifier' => $userLanguage->getIdentifier(),
                'can_login'                => $canLogin,
                'is_gravatar_allowed'      => $isGravatarAllowed,
            ],
        ];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getByUsername($username);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testGetByEmail()
    {
        $id                = 123;
        $username          = 'foo';
        $email             = 'foo@example.com';
        $password          = '';
        $userLanguage      = new UserLanguage(72, 'baz', 'Baz');
        $canLogin          = true;
        $isGravatarAllowed = true;

        $sql          = 'SELECT users.id, users.username, users.email, users.password, users.user_language_id, ul.identifier AS user_language_identifier, users.can_login, users.is_gravatar_allowed, GROUP_CONCAT(ug.id) AS user_group_ids, GROUP_CONCAT(ug.identifier) AS user_group_identifiers, GROUP_CONCAT(ug.name) AS user_group_names FROM users INNER JOIN user_languages AS ul ON ul.id = users.user_language_id AND ul.deleted = 0 LEFT JOIN users_user_groups AS uug ON uug.user_id = users.id AND uug.deleted = 0 LEFT JOIN user_groups AS ug ON ug.id = uug.user_group_id AND ug.deleted = 0 WHERE (users.deleted = 0) AND (email = :email)'; // phpcs:ignore
        $values       = ['email' => [$email, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'                       => $id,
                'username'                 => $username,
                'email'                    => $email,
                'password'                 => $password,
                'user_language_id'         => $userLanguage->getId(),
                'user_language_identifier' => $userLanguage->getIdentifier(),
                'can_login'                => $canLogin,
                'is_gravatar_allowed'      => $isGravatarAllowed,
            ],
        ];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getByEmail($email);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testFindByUsername()
    {
        $id                = 123;
        $username          = 'foo';
        $email             = 'foo@example.com';
        $password          = '';
        $userLanguage      = new UserLanguage(72, 'baz', 'Baz');
        $canLogin          = true;
        $isGravatarAllowed = true;

        $sql          = 'SELECT users.id, users.username, users.email, users.password, users.user_language_id, ul.identifier AS user_language_identifier, users.can_login, users.is_gravatar_allowed, GROUP_CONCAT(ug.id) AS user_group_ids, GROUP_CONCAT(ug.identifier) AS user_group_identifiers, GROUP_CONCAT(ug.name) AS user_group_names FROM users INNER JOIN user_languages AS ul ON ul.id = users.user_language_id AND ul.deleted = 0 LEFT JOIN users_user_groups AS uug ON uug.user_id = users.id AND uug.deleted = 0 LEFT JOIN user_groups AS ug ON ug.id = uug.user_group_id AND ug.deleted = 0 WHERE (users.deleted = 0) AND ((username = :identifier OR email = :identifier))'; // phpcs:ignore
        $values       = ['identifier' => [$username, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'                       => $id,
                'username'                 => $username,
                'email'                    => $email,
                'password'                 => $password,
                'user_language_id'         => $userLanguage->getId(),
                'user_language_identifier' => $userLanguage->getIdentifier(),
                'can_login'                => $canLogin,
                'is_gravatar_allowed'      => $isGravatarAllowed,
            ],
        ];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->find($username);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testFindByEmail()
    {
        $id                = 123;
        $username          = 'foo';
        $email             = 'foo@example.com';
        $password          = '';
        $userLanguage      = new UserLanguage(72, 'baz', 'Baz');
        $canLogin          = true;
        $isGravatarAllowed = true;

        $sql          = 'SELECT users.id, users.username, users.email, users.password, users.user_language_id, ul.identifier AS user_language_identifier, users.can_login, users.is_gravatar_allowed, GROUP_CONCAT(ug.id) AS user_group_ids, GROUP_CONCAT(ug.identifier) AS user_group_identifiers, GROUP_CONCAT(ug.name) AS user_group_names FROM users INNER JOIN user_languages AS ul ON ul.id = users.user_language_id AND ul.deleted = 0 LEFT JOIN users_user_groups AS uug ON uug.user_id = users.id AND uug.deleted = 0 LEFT JOIN user_groups AS ug ON ug.id = uug.user_group_id AND ug.deleted = 0 WHERE (users.deleted = 0) AND ((username = :identifier OR email = :identifier))'; // phpcs:ignore
        $values       = ['identifier' => [$email, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'                       => $id,
                'username'                 => $username,
                'email'                    => $email,
                'password'                 => $password,
                'user_language_id'         => $userLanguage->getId(),
                'user_language_identifier' => $userLanguage->getIdentifier(),
                'can_login'                => $canLogin,
                'is_gravatar_allowed'      => $isGravatarAllowed,
            ],
        ];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->find($email);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testUpdateWithoutRelated()
    {
        $id                = 123;
        $username          = 'foo';
        $email             = 'foo@example.com';
        $password          = '';
        $userLanguage      = new UserLanguage(72, 'baz', 'Baz');
        $canLogin          = true;
        $isGravatarAllowed = true;

        $sql0    = 'UPDATE users AS users SET username = ?, email = ?, password = ?, user_language_id = ?, can_login = ?, is_gravatar_allowed = ? WHERE (id = ?)'; // phpcs:ignore
        $values0 = [
            [$username, \PDO::PARAM_STR],
            [$email, \PDO::PARAM_STR],
            [$password, \PDO::PARAM_STR],
            [$userLanguage->getId(), \PDO::PARAM_INT],
            [$canLogin, \PDO::PARAM_INT],
            [$isGravatarAllowed, \PDO::PARAM_INT],
            [$id, \PDO::PARAM_INT],
        ];
        $this->prepare($sql0, $this->createWriteStatement($values0), 0);

        $sql1    = 'DELETE FROM users_user_groups WHERE (user_id = ?)'; // phpcs:ignore
        $values1 = [[$id, \PDO::PARAM_INT]];
        $this->prepare($sql1, $this->createWriteStatement($values1), 1);

        $entity = new User($id, $username, $email, $password, $canLogin, $isGravatarAllowed, $userLanguage);
        $this->sut->update($entity);
    }

    public function testUpdateWithRelated()
    {
        $id                = 123;
        $username          = 'foo';
        $email             = 'foo@example.com';
        $password          = '';
        $userLanguage      = new UserLanguage(72, 'baz', 'Baz');
        $canLogin          = true;
        $isGravatarAllowed = true;
        $userGroups        = [new UserGroup(38, 'ug-38', 'UG 38'), new UserGroup(51, 'ug-51', 'UG 51')];

        $sql0    = 'UPDATE users AS users SET username = ?, email = ?, password = ?, user_language_id = ?, can_login = ?, is_gravatar_allowed = ? WHERE (id = ?)'; // phpcs:ignore
        $values0 = [
            [$username, \PDO::PARAM_STR],
            [$email, \PDO::PARAM_STR],
            [$password, \PDO::PARAM_STR],
            [$userLanguage->getId(), \PDO::PARAM_INT],
            [$canLogin, \PDO::PARAM_INT],
            [$isGravatarAllowed, \PDO::PARAM_INT],
            [$id, \PDO::PARAM_INT],
        ];
        $this->prepare($sql0, $this->createWriteStatement($values0), 0);

        $sql1    = 'DELETE FROM users_user_groups WHERE (user_id = ?)'; // phpcs:ignore
        $values1 = [[$id, \PDO::PARAM_INT]];
        $this->prepare($sql1, $this->createWriteStatement($values1), 1);

        $sql2    = 'INSERT INTO users_user_groups (user_id, user_group_id) VALUES (?, ?)'; // phpcs:ignore
        $values2 = [[$id, \PDO::PARAM_INT], [$userGroups[0]->getId(), \PDO::PARAM_INT]];
        $this->prepare($sql2, $this->createWriteStatement($values2), 2);

        $sql3    = 'INSERT INTO users_user_groups (user_id, user_group_id) VALUES (?, ?)'; // phpcs:ignore
        $values3 = [[$id, \PDO::PARAM_INT], [$userGroups[1]->getId(), \PDO::PARAM_INT]];
        $this->prepare($sql3, $this->createWriteStatement($values3), 3);

        $entity = new User(
            $id,
            $username,
            $email,
            $password,
            $canLogin,
            $isGravatarAllowed,
            $userLanguage,
            $userGroups
        );
        $this->sut->update($entity);
    }

    /**
     * @param array $expectedData
     * @param User  $entity
     */
    protected function assertEntity(array $expectedData, $entity)
    {
        $this->assertInstanceOf(User::class, $entity);
        $this->assertEquals($expectedData['id'], $entity->getId());
        $this->assertSame($expectedData['username'], $entity->getUsername());
        $this->assertSame($expectedData['email'], $entity->getEmail());
        $this->assertSame($expectedData['password'], $entity->getPassword());
        $this->assertSame($expectedData['user_language_id'], $entity->getUserLanguage()->getId());
        $this->assertSame($expectedData['user_language_identifier'], $entity->getUserLanguage()->getIdentifier());
        $this->assertSame($expectedData['can_login'], $entity->canLogin());
        $this->assertSame($expectedData['is_gravatar_allowed'], $entity->isGravatarAllowed());
    }
}
