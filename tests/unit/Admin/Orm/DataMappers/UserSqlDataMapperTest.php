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

    public function testAdd()
    {
        $nextId            = '123';
        $username          = 'foo';
        $email             = 'foo@example.com';
        $password          = '';
        $userGroup         = new UserGroup(55, 'bar', 'Bar');
        $userLanguage      = new UserLanguage(72, 'baz', 'Baz');
        $canLogin          = true;
        $isGravatarAllowed = true;

        $sql    = 'INSERT INTO users (username, email, password, user_group_id, user_language_id, can_login, is_gravatar_allowed) VALUES (?, ?, ?, ?, ?, ?, ?)'; // phpcs:ignore
        $values = [
            [$username, \PDO::PARAM_STR],
            [$email, \PDO::PARAM_STR],
            [$password, \PDO::PARAM_STR],
            [$userGroup->getId(), \PDO::PARAM_INT],
            [$userLanguage->getId(), \PDO::PARAM_INT],
            [$canLogin, \PDO::PARAM_INT],
            [$isGravatarAllowed, \PDO::PARAM_INT],
        ];

        $this->lastInsertId($nextId);
        $this->prepare($sql, $this->createWriteStatement($values));
        $entity = new User(0, $username, $email, $password, $userGroup, $userLanguage, $canLogin, $isGravatarAllowed);

        $this->sut->add($entity);

        $this->assertSame($nextId, $entity->getId());
    }

    public function testDelete()
    {
        $id                = 123;
        $username          = 'foo';
        $email             = 'foo@example.com';
        $password          = '';
        $userGroup         = new UserGroup(55, 'bar', 'Bar');
        $userLanguage      = new UserLanguage(72, 'baz', 'Baz');
        $canLogin          = true;
        $isGravatarAllowed = true;

        $sql    = 'UPDATE users AS users SET deleted = ? WHERE (id = ?)'; // phpcs:ignore
        $values = [[1, \PDO::PARAM_INT], [$id, \PDO::PARAM_INT]];

        $this->prepare($sql, $this->createWriteStatement($values));
        $entity = new User($id, $username, $email, $password, $userGroup, $userLanguage, $canLogin, $isGravatarAllowed);

        $this->sut->delete($entity);
    }

    public function testGetAll()
    {
        $id                = 123;
        $username          = 'foo';
        $email             = 'foo@example.com';
        $password          = '';
        $userGroup         = new UserGroup(55, 'bar', 'Bar');
        $userLanguage      = new UserLanguage(72, 'baz', 'Baz');
        $canLogin          = true;
        $isGravatarAllowed = true;

        $sql          = 'SELECT users.id, users.username, users.email, users.password, users.user_group_id, user_groups.identifier AS user_group_identifier, user_groups.name AS user_group_name, users.user_language_id, user_languages.identifier AS user_language_identifier, users.can_login, users.is_gravatar_allowed FROM users INNER JOIN user_groups AS user_groups ON user_groups.id = users.user_group_id AND user_groups.deleted = 0 INNER JOIN user_languages AS user_languages ON user_languages.id = users.user_language_id AND user_languages.deleted = 0 WHERE (users.deleted = 0)'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'                       => $id,
                'username'                 => $username,
                'email'                    => $email,
                'password'                 => $password,
                'user_group_id'            => $userGroup->getId(),
                'user_group_identifier'    => $userGroup->getIdentifier(),
                'user_group_name'          => $userGroup->getName(),
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
        $userGroup         = new UserGroup(55, 'bar', 'Bar');
        $userLanguage      = new UserLanguage(72, 'baz', 'Baz');
        $canLogin          = true;
        $isGravatarAllowed = true;

        $sql          = 'SELECT users.id, users.username, users.email, users.password, users.user_group_id, user_groups.identifier AS user_group_identifier, user_groups.name AS user_group_name, users.user_language_id, user_languages.identifier AS user_language_identifier, users.can_login, users.is_gravatar_allowed FROM users INNER JOIN user_groups AS user_groups ON user_groups.id = users.user_group_id AND user_groups.deleted = 0 INNER JOIN user_languages AS user_languages ON user_languages.id = users.user_language_id AND user_languages.deleted = 0 WHERE (users.deleted = 0) AND (users.id = :user_id)'; // phpcs:ignore
        $values       = ['user_id' => [$id, \PDO::PARAM_INT]];
        $expectedData = [
            [
                'id'                       => $id,
                'username'                 => $username,
                'email'                    => $email,
                'password'                 => $password,
                'user_group_id'            => $userGroup->getId(),
                'user_group_identifier'    => $userGroup->getIdentifier(),
                'user_group_name'          => $userGroup->getName(),
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
        $userGroup         = new UserGroup(55, 'bar', 'Bar');
        $userLanguage      = new UserLanguage(72, 'baz', 'Baz');
        $canLogin          = true;
        $isGravatarAllowed = true;

        $sql          = 'SELECT users.id, users.username, users.email, users.password, users.user_group_id, user_groups.identifier AS user_group_identifier, user_groups.name AS user_group_name, users.user_language_id, user_languages.identifier AS user_language_identifier, users.can_login, users.is_gravatar_allowed FROM users INNER JOIN user_groups AS user_groups ON user_groups.id = users.user_group_id AND user_groups.deleted = 0 INNER JOIN user_languages AS user_languages ON user_languages.id = users.user_language_id AND user_languages.deleted = 0 WHERE (users.deleted = 0) AND (`username` = :username)'; // phpcs:ignore
        $values       = ['username' => [$username, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'                       => $id,
                'username'                 => $username,
                'email'                    => $email,
                'password'                 => $password,
                'user_group_id'            => $userGroup->getId(),
                'user_group_identifier'    => $userGroup->getIdentifier(),
                'user_group_name'          => $userGroup->getName(),
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
        $userGroup         = new UserGroup(55, 'bar', 'Bar');
        $userLanguage      = new UserLanguage(72, 'baz', 'Baz');
        $canLogin          = true;
        $isGravatarAllowed = true;

        $sql          = 'SELECT users.id, users.username, users.email, users.password, users.user_group_id, user_groups.identifier AS user_group_identifier, user_groups.name AS user_group_name, users.user_language_id, user_languages.identifier AS user_language_identifier, users.can_login, users.is_gravatar_allowed FROM users INNER JOIN user_groups AS user_groups ON user_groups.id = users.user_group_id AND user_groups.deleted = 0 INNER JOIN user_languages AS user_languages ON user_languages.id = users.user_language_id AND user_languages.deleted = 0 WHERE (users.deleted = 0) AND (email = :email)'; // phpcs:ignore
        $values       = ['email' => [$email, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'                       => $id,
                'username'                 => $username,
                'email'                    => $email,
                'password'                 => $password,
                'user_group_id'            => $userGroup->getId(),
                'user_group_identifier'    => $userGroup->getIdentifier(),
                'user_group_name'          => $userGroup->getName(),
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
        $userGroup         = new UserGroup(55, 'bar', 'Bar');
        $userLanguage      = new UserLanguage(72, 'baz', 'Baz');
        $canLogin          = true;
        $isGravatarAllowed = true;

        $sql          = 'SELECT users.id, users.username, users.email, users.password, users.user_group_id, user_groups.identifier AS user_group_identifier, user_groups.name AS user_group_name, users.user_language_id, user_languages.identifier AS user_language_identifier, users.can_login, users.is_gravatar_allowed FROM users INNER JOIN user_groups AS user_groups ON user_groups.id = users.user_group_id AND user_groups.deleted = 0 INNER JOIN user_languages AS user_languages ON user_languages.id = users.user_language_id AND user_languages.deleted = 0 WHERE (users.deleted = 0) AND ((username = :identifier OR email = :identifier))'; // phpcs:ignore
        $values       = ['identifier' => [$username, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'                       => $id,
                'username'                 => $username,
                'email'                    => $email,
                'password'                 => $password,
                'user_group_id'            => $userGroup->getId(),
                'user_group_identifier'    => $userGroup->getIdentifier(),
                'user_group_name'          => $userGroup->getName(),
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
        $userGroup         = new UserGroup(55, 'bar', 'Bar');
        $userLanguage      = new UserLanguage(72, 'baz', 'Baz');
        $canLogin          = true;
        $isGravatarAllowed = true;

        $sql          = 'SELECT users.id, users.username, users.email, users.password, users.user_group_id, user_groups.identifier AS user_group_identifier, user_groups.name AS user_group_name, users.user_language_id, user_languages.identifier AS user_language_identifier, users.can_login, users.is_gravatar_allowed FROM users INNER JOIN user_groups AS user_groups ON user_groups.id = users.user_group_id AND user_groups.deleted = 0 INNER JOIN user_languages AS user_languages ON user_languages.id = users.user_language_id AND user_languages.deleted = 0 WHERE (users.deleted = 0) AND ((username = :identifier OR email = :identifier))'; // phpcs:ignore
        $values       = ['identifier' => [$email, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'                       => $id,
                'username'                 => $username,
                'email'                    => $email,
                'password'                 => $password,
                'user_group_id'            => $userGroup->getId(),
                'user_group_identifier'    => $userGroup->getIdentifier(),
                'user_group_name'          => $userGroup->getName(),
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

    public function testUpdate()
    {
        $id                = 123;
        $username          = 'foo';
        $email             = 'foo@example.com';
        $password          = '';
        $userGroup         = new UserGroup(55, 'bar', 'Bar');
        $userLanguage      = new UserLanguage(72, 'baz', 'Baz');
        $canLogin          = true;
        $isGravatarAllowed = true;

        $sql    = 'UPDATE users AS users SET username = ?, email = ?, password = ?, user_group_id = ?, user_language_id = ?, can_login = ?, is_gravatar_allowed = ? WHERE (id = ?)'; // phpcs:ignore
        $values = [
            [$username, \PDO::PARAM_STR],
            [$email, \PDO::PARAM_STR],
            [$password, \PDO::PARAM_STR],
            [$userGroup->getId(), \PDO::PARAM_INT],
            [$userLanguage->getId(), \PDO::PARAM_INT],
            [$canLogin, \PDO::PARAM_INT],
            [$isGravatarAllowed, \PDO::PARAM_INT],
            [$id, \PDO::PARAM_INT],
        ];

        $this->prepare($sql, $this->createWriteStatement($values));
        $entity = new User($id, $username, $email, $password, $userGroup, $userLanguage, $canLogin, $isGravatarAllowed);

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
        $this->assertSame($expectedData['user_group_id'], $entity->getUserGroup()->getId());
        $this->assertSame($expectedData['user_group_identifier'], $entity->getUserGroup()->getIdentifier());
        $this->assertSame($expectedData['user_group_name'], $entity->getUserGroup()->getName());
        $this->assertSame($expectedData['user_language_id'], $entity->getUserLanguage()->getId());
        $this->assertSame($expectedData['user_language_identifier'], $entity->getUserLanguage()->getIdentifier());
        $this->assertSame($expectedData['can_login'], $entity->canLogin());
        $this->assertSame($expectedData['is_gravatar_allowed'], $entity->isGravatarAllowed());
    }
}
