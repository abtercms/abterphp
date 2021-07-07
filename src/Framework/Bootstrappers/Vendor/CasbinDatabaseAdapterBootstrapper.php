<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Bootstrappers\Vendor;

use AbterPhp\Framework\Constant\Env;
use AbterPhp\Framework\Environments\Environment;
use AbterPhp\Framework\Exception\Config;
use CasbinAdapter\Database\Adapter;
use Opulence\Databases\Adapters\Pdo\MySql\Driver as MySqlDriver;
use Opulence\Databases\Adapters\Pdo\PostgreSql\Driver as PostgreSqlDriver;
use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\Bootstrappers\ILazyBootstrapper;
use Opulence\Ioc\IContainer;

class CasbinDatabaseAdapterBootstrapper extends Bootstrapper implements ILazyBootstrapper
{
    /**
     * @inheritdoc
     */
    public function getBindings(): array
    {
        return [
            Adapter::class,
        ];
    }

    /**
     * @param IContainer $container
     *
     * @throws Config
     */
    public function registerBindings(IContainer $container): void
    {
        $driverClass = Environment::getVar('DB_DRIVER', PostgreSqlDriver::class);

        switch ($driverClass) {
            case MySqlDriver::class:
                $dirDriver = 'mysql';
                break;
            case PostgreSqlDriver::class:
                $dirDriver = 'pgsql';
                break;
            default:
                throw new Config(
                    "Invalid database driver type specified in environment var \"DB_DRIVER\": $driverClass"
                );
        }

        $config = [
            'type'     => $dirDriver,
            'hostname' => Environment::mustGetVar(Env::PDO_WRITE_HOST),
            'database' => Environment::mustGetVar(Env::PDO_WRITE_DATABASE),
            'username' => Environment::mustGetVar(Env::PDO_WRITE_USERNAME),
            'password' => Environment::mustGetVar(Env::PDO_WRITE_PASSWORD),
            'hostport' => Environment::mustGetVar(Env::PDO_WRITE_PORT),
        ];

        $adapter = Adapter::newAdapter($config);

        $container->bindInstance(Adapter::class, $adapter);
    }
}
