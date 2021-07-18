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
use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\Bootstrappers\ILazyBootstrapper;
use Opulence\Ioc\IContainer;
use Opulence\Ioc\IocException;
use Opulence\Orm\Ids\Generators\IIdGeneratorRegistry;
use QB\Generic\QueryBuilder\IQueryBuilder;
use RuntimeException;

/**
 * Defines the ORM bootstrapper
 */
class OrmBootstrapper extends Bootstrapper implements ILazyBootstrapper
{
    /** @var string[] */
    protected array $baseBindings = [
        IIdGeneratorRegistry::class,
    ];

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
        return array_merge($this->baseBindings, $this->repoMappers);
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
            $qb     = $container->resolve(IQueryBuilder::class);
            foreach ($this->repoMappers as $repoClass) {
                $container->bindInstance($repoClass, new $repoClass($writer, $qb));
            }
        } catch (IocException $ex) {
            throw new RuntimeException('Failed to register ORM bindings', 0, $ex);
        }
    }
}
