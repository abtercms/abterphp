<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Tests\Bootstrappers\Vendor;

use AbterPhp\Framework\Bootstrappers\Vendor\CasbinDatabaseAdapterBootstrapper;
use AbterPhp\Framework\Environments\Environment;
use AbterPhp\Framework\Exception\Config;
use Opulence\Databases\Adapters\Pdo\MySql\Driver as MySqlDriver;
use Opulence\Databases\Adapters\Pdo\PostgreSql\Driver as PostgreSqlDriver;
use Opulence\Ioc\Container;
use PDOException;
use PHPUnit\Framework\TestCase;

class CasbinDatabaseAdapterBootstrapperTest extends TestCase
{
    /** @var CasbinDatabaseAdapterBootstrapper - System Under Test */
    protected CasbinDatabaseAdapterBootstrapper $sut;

    public function setUp(): void
    {
        Environment::unsetVar('DB_DRIVER');
        Environment::setVar('PDO_WRITE_HOST', 'db');
        Environment::setVar('PDO_WRITE_DATABASE', 'test');
        Environment::setVar('PDO_WRITE_PASSWORD', 'pass');
        Environment::setVar('PDO_WRITE_PORT', '4321');
        Environment::forceSetVar('PDO_WRITE_USERNAME', 'nope');

        $this->sut = new CasbinDatabaseAdapterBootstrapper();
    }

    public function testRegisterBindingsPostgresTriesToConnectToDB(): void
    {
        Environment::setVar('DB_DRIVER', PostgreSqlDriver::class);

        $this->expectException(PDOException::class);

        $container = new Container();

        $this->sut->registerBindings($container);
    }

    public function testRegisterBindingsMySQLTriesToConnectToDB(): void
    {
        Environment::setVar('DB_DRIVER', MySqlDriver::class);

        $this->expectException(PDOException::class);

        $container = new Container();

        $this->sut->registerBindings($container);
    }

    public function testRegisterBindingsThrowsInvalidDriverException(): void
    {
        $dbDriverClassStub = 'FooClass';

        Environment::setVar('DB_DRIVER', $dbDriverClassStub);

        $this->expectException(Config::class);
        $this->expectErrorMessageMatches("/$dbDriverClassStub/");

        $container = new Container();

        $this->sut->registerBindings($container);
    }
}
