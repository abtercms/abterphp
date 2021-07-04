<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Bootstrappers\Orm;

use AbterPhp\Admin\Orm\AdminResourceRepo;
use AbterPhp\Admin\Orm\ApiClientRepo;
use AbterPhp\Admin\Orm\LoginAttemptRepo;
use AbterPhp\Admin\Orm\UserGroupRepo;
use AbterPhp\Admin\Orm\UserLanguageRepo;
use AbterPhp\Admin\Orm\UserRepo;
use AbterPhp\Framework\Database\PDO\Writer;
use AbterPhp\Framework\Orm\Ids\Generators\IdGeneratorRegistry;
use Opulence\Databases\ConnectionPools\ConnectionPool;
use Opulence\Databases\IConnection;
use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\Bootstrappers\ILazyBootstrapper;
use Opulence\Ioc\IContainer;
use Opulence\Ioc\IocException;
use Opulence\Orm\Ids\Generators\IIdGeneratorRegistry;
use Opulence\Orm\IUnitOfWork;
use Opulence\Orm\UnitOfWork;
use QB\Generic\QueryBuilder\IQueryBuilder;
use RuntimeException;

/**
 * Defines the ORM bootstrapper
 */
class OrmBootstrapper extends Bootstrapper implements ILazyBootstrapper
{
    /** @var string[] */
    protected array $repoMappers = [
        AdminResourceRepo::class,
        LoginAttemptRepo::class,
        ApiClientRepo::class,
        UserGroupRepo::class,
        UserLanguageRepo::class,
        UserRepo::class,
    ];

    /**
     * @return string[]
     */
    public function getBindings(): array
    {
        $baseBindings = [
            IIdGeneratorRegistry::class,
        ];

        return array_merge($baseBindings, $this->repoMappers);
    }

    /**
     * @inheritdoc
     */
    public function registerBindings(IContainer $container)
    {
        try {
            $idGeneratorRegistry = new IdGeneratorRegistry();
            $container->bindInstance(IIdGeneratorRegistry::class, $idGeneratorRegistry);

            $writer = $container->resolve(Writer::class);
            $qb = $container->resolve(IQueryBuilder::class);
            foreach ($this->repoMappers as $repoClass) {
                $container->bindInstance($repoClass, new $repoClass($writer, $qb));
            }
        } catch (IocException $ex) {
            throw new RuntimeException('Failed to register ORM bindings', 0, $ex);
        }
    }

    /**
     * Binds repositories to the container
     *
     * @param IContainer  $container  The container to bind to
     * @param IUnitOfWork $unitOfWork The unit of work to use in repositories
     *
     * @throws IocException
     */
    protected function bindRepositories(IContainer $container, UnitOfWork $unitOfWork)
    {
        $connectionPool  = $container->resolve(ConnectionPool::class);
        $readConnection  = $connectionPool->getReadConnection();
        $writeConnection = $connectionPool->getWriteConnection();

        foreach ($this->repoMappers as $repoClass => $classes) {
            $container->bindFactory(
                $repoClass,
                $this->createFactory(
                    $repoClass,
                    $classes[0],
                    $classes[1],
                    $readConnection,
                    $writeConnection,
                    $unitOfWork
                )
            );
        }
    }

    /**
     * @param string      $repoClass
     * @param string      $dataMapperClass
     * @param string      $entityClass
     * @param IConnection $readConnection
     * @param IConnection $writeConnection
     * @param IUnitOfWork $unitOfWork
     *
     * @return \Closure
     */
    private function createFactory(
        string $repoClass,
        string $dataMapperClass,
        string $entityClass,
        IConnection $readConnection,
        IConnection $writeConnection,
        IUnitOfWork $unitOfWork
    ): \Closure {
        return function () use (
            $repoClass,
            $dataMapperClass,
            $entityClass,
            $readConnection,
            $writeConnection,
            $unitOfWork
        ) {
            $dataMapper = new $dataMapperClass($readConnection, $writeConnection);

            return new $repoClass($entityClass, $dataMapper, $unitOfWork);
        };
    }
}
