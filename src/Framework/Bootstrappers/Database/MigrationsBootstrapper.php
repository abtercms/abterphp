<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Bootstrappers\Database;

use AbterPhp\Framework\Database\Migrations\PdoMigrationRepository;
use AbterPhp\Framework\Database\Migrations\Migrator;
use AbterPhp\Framework\Database\PDO\Writer;
use Opulence\Databases\Migrations\IExecutedMigrationRepository;
use Opulence\Databases\Migrations\IMigrator;
use Opulence\Databases\Providers\Types\TypeMapper;
use Opulence\Framework\Configuration\Config;
use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\Bootstrappers\ILazyBootstrapper;
use Opulence\Ioc\IContainer;
use Opulence\Ioc\IocException;
use QB\Generic\QueryBuilder\IQueryBuilder;

class MigrationsBootstrapper extends Bootstrapper implements ILazyBootstrapper
{
    /** @var array|null */
    protected ?array $migrationPaths = null;
    /**
     * @inheritdoc
     */
    public function getBindings(): array
    {
        return [
            IMigrator::class,
            IExecutedMigrationRepository::class,
        ];
    }

    /**
     * @return array
     */
    public function getMigrationPaths(): array
    {
        global $abterModuleManager;

        if ($this->migrationPaths !== null) {
            return $this->migrationPaths;
        }

        $this->migrationPaths = $abterModuleManager->getMigrationPaths() ?: [];

        assert(is_array($this->migrationPaths));

        return $this->migrationPaths;
    }

    /**
     * @param array $migrationPaths
     *
     * @return $this
     */
    public function setMigrationPaths(array $migrationPaths): self
    {
        $this->migrationPaths = $migrationPaths;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function registerBindings(IContainer $container): void
    {
        $paths = $this->fixMigrationPaths();

        $writer = $container->resolve(Writer::class);

        $executedMigrationRepository = $this->registerExecutedMigrationRepository($container, $writer);

        $migrator = new Migrator($paths, $writer, $container, $executedMigrationRepository);

        $container->bindInstance(IMigrator::class, $migrator);
    }

    protected function fixMigrationPaths(): array
    {
        /** @phan-suppress-next-line PhanTypeMismatchArgumentProbablyReal */
        $globalPaths = (array)Config::get('paths', 'database.migrations', []);

        $modulePaths = $this->getMigrationPaths();

        $paths = array_merge($globalPaths, $modulePaths);

        Config::set('paths', 'database.migrations', $paths);

        return $paths;
    }

    /**
     * @param IContainer $container
     * @param Writer     $writer
     *
     * @return IExecutedMigrationRepository
     * @throws IocException
     */
    protected function registerExecutedMigrationRepository(IContainer $container, Writer $writer): IExecutedMigrationRepository
    {
        $queryBuilder = $container->resolve(IQueryBuilder::class);

        $executedMigrationRepository = new PdoMigrationRepository($writer, $queryBuilder, new TypeMapper());

        $container->bindInstance(IExecutedMigrationRepository::class, $executedMigrationRepository);

        return $executedMigrationRepository;
    }
}
