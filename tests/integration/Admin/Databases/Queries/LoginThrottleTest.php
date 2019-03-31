<?php

declare(strict_types=1);

namespace Integration\AbterPhp\Admin\Databases\Queries;

use AbterPhp\Admin\Databases\Queries\LoginThrottle;
use AbterPhp\Framework\Crypto\Crypto;
use AbterPhp\Framework\Helper\UuidV4;
use Integration\Framework\Database\IntegrationTestCase;
use Opulence\Databases\ConnectionPools\ConnectionPool;
use Opulence\Databases\IConnection;
use Opulence\QueryBuilders\MySql\QueryBuilder;

class LoginThrottleTest extends IntegrationTestCase
{
    const RAW_PASSWORD = '1234';

    const IP_HASH = 'qwerty';

    /** @var LoginThrottle */
    protected $sut;

    /** @var ConnectionPool */
    protected $connectionPool;

    /** @var IConnection */
    protected $connection;

    /** @var Crypto */
    protected $crypto;

    /** @var array */
    protected $userLanguageData = [];

    /** @var array */
    protected $userGroupData = [];

    /** @var array */
    protected $userData = [];

    /** @var string[] */
    protected $loginAttemptIds;

    public function setUp()
    {
        parent::setUp();

        $this->connectionPool = $this->container->resolve(ConnectionPool::class);
        $this->connection     = $this->container->resolve(IConnection::class);
        $this->crypto         = $this->container->resolve(Crypto::class);

        $this->loginAttemptIds = [];

        $this->populateUserLanguages();
        $this->populateUserGroups();
        $this->populateUsers();

        $this->sut = new LoginThrottle($this->connectionPool);
    }

    public function testSuccessWithNoPreviousAttempt()
    {
        $actualResult = $this->sut->isLoginAllowed(static::IP_HASH, $this->userData['username'], 1);

        $this->assertTrue($actualResult);
    }

    public function testSuccessWithOnePreviousAttempt()
    {
        $this->populateLoginAttempts();

        $actualResult = $this->sut->isLoginAllowed(static::IP_HASH, $this->userData['username'], 2);

        $this->assertTrue($actualResult);
    }

    public function testFailureWithOnePreviousAttempt()
    {
        $this->populateLoginAttempts();

        $actualResult = $this->sut->isLoginAllowed(static::IP_HASH, $this->userData['username'], 1);

        $this->assertFalse($actualResult);
    }

    protected function populateUserLanguages()
    {
        $uuid = UuidV4::generate();
        $name = 'ul-' . rand(100, 999);

        $this->userLanguageData = [
            'id'         => $uuid,
            'identifier' => substr($uuid, 0, 5),
            'name'       => $name,
        ];

        $query = (new QueryBuilder())->insert('user_languages', $this->userLanguageData);

        $statement = $this->connection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();
    }

    protected function populateUserGroups()
    {
        $uuid = UuidV4::generate();
        $name = 'ug-' . rand(100, 999);

        $this->userGroupData = [
            'id'         => $uuid,
            'identifier' => substr($uuid, 0, 5),
            'name'       => $name,
        ];

        $query = (new QueryBuilder())->insert('user_groups', $this->userGroupData);

        $statement = $this->connection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();
    }

    protected function populateUsers()
    {
        $preparedPassword = $this->crypto->prepareSecret(static::RAW_PASSWORD);
        $packedPassword   = $this->crypto->hashCrypt($preparedPassword);

        $userLanguageId = $this->userLanguageData['id'];
        $uuid           = UuidV4::generate();
        $username       = 'u-' . rand(100, 999);
        $email          = "$username@example.com";

        $this->userData = [
            'id'                  => $uuid,
            'username'            => $username,
            'email'               => $email,
            'password'            => $packedPassword,
            'user_language_id'    => $userLanguageId,
            'can_login'           => 1,
            'is_gravatar_allowed' => 0,
        ];

        $query = (new QueryBuilder())->insert('users', $this->userData);

        $statement = $this->connection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();
    }

    protected function populateLoginAttempts()
    {
        $this->loginAttemptIds[] = UuidV4::generate();

        $data = [
            'id'       => $this->loginAttemptIds[count($this->loginAttemptIds) - 1],
            'ip_hash'  => static::IP_HASH,
            'username' => $this->userData['username'],
        ];

        $query = (new QueryBuilder())->insert('login_attempts', $data);

        $statement = $this->connection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->tearDownLoginAttempts();
        $this->tearDownUsers();
        $this->tearDownUserGroups();
        $this->tearDownUserLanguages();
    }

    protected function tearDownLoginAttempts()
    {
        foreach ($this->loginAttemptIds as $loginAttemptId) {
            $query = (new QueryBuilder())
                ->delete('login_attempts')
                ->where('id = ?')
                ->addUnnamedPlaceholderValue($loginAttemptId, \PDO::PARAM_STR);

            $statement = $this->connection->prepare($query->getSql());
            $statement->bindValues($query->getParameters());
            $statement->execute();
        }
    }

    protected function tearDownUsers()
    {
        $query = (new QueryBuilder())
            ->delete('users')
            ->where('id = ?')
            ->addUnnamedPlaceholderValue($this->userData['id'], \PDO::PARAM_STR);

        $statement = $this->connection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();
    }

    protected function tearDownUserGroups()
    {
        $query = (new QueryBuilder())
            ->delete('user_groups')
            ->where('id = ?')
            ->addUnnamedPlaceholderValue($this->userGroupData['id'], \PDO::PARAM_STR);

        $statement = $this->connection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();
    }

    protected function tearDownUserLanguages()
    {
        $query = (new QueryBuilder())
            ->delete('user_languages')
            ->where('id = ?')
            ->addUnnamedPlaceholderValue($this->userLanguageData['id'], \PDO::PARAM_STR);

        $statement = $this->connection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();
    }
}
