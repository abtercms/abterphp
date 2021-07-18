<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Database\Migration;

use AbterPhp\Framework\Database\PDO\Writer;
use Exception;
use Opulence\Databases\Migrations\IExecutedMigrationRepository;
use Opulence\Databases\Migrations\IMigration;
use Opulence\Databases\Migrations\IMigrator;
use Opulence\Ioc\IContainer;
use Opulence\Ioc\IocException;
use QB\Generic\Statement\Command;

class Migrator implements IMigrator
{
    protected array $allMigrationClasses;
    protected Writer $writer;
    protected IContainer $container;
    protected IExecutedMigrationRepository $executedMigrations;

    /**
     * Migrator constructor.
     *
     * @param array                        $allMigrationClasses
     * @param Writer                       $writer
     * @param IContainer                   $container
     * @param IExecutedMigrationRepository $executedMigrations
     */
    public function __construct(
        array $allMigrationClasses,
        Writer $writer,
        IContainer $container,
        IExecutedMigrationRepository $executedMigrations
    ) {
        $this->allMigrationClasses = $allMigrationClasses;
        $this->writer              = $writer;
        $this->container           = $container;
        $this->executedMigrations  = $executedMigrations;
    }

    /**
     * Rolls back all migrations
     *
     * @return string[] The list of rolled back migration classes
     * @throws IocException
     */
    public function rollBackAllMigrations(): array
    {
        // These classes are returned in chronologically descending order
        $migrationClasses = $this->executedMigrations->getAll();
        $migrations       = $this->resolveManyMigrations($migrationClasses);

        $this->executeRollBacks($migrations);

        return $migrationClasses;
    }

    /**
     * @inheritdoc
     * @throws IocException
     */
    public function rollBackMigrations(int $number = 1): array
    {
        // These classes are returned in chronologically descending order
        $migrationClasses = $this->executedMigrations->getLast($number);
        $migrations       = $this->resolveManyMigrations($migrationClasses);

        $this->executeRollBacks($migrations);

        return $migrationClasses;
    }

    /**
     * Executes the roll backs on a list of migrations
     *
     * @param IMigration[] $migrations The migrations to execute the down method on
     */
    private function executeRollBacks(array $migrations): void
    {
        $executedMigrations = $this->executedMigrations;

        try {
            $this->writer->execute(new Command('TRANSACTION'));
            foreach ($migrations as $migration) {
                $migration->down();

                $executedMigrations->delete(get_class($migration));
            }
            $this->writer->execute(new Command('COMMIT'));
        } catch (Exception $e) {
            // returning false will trigger a rollback
            $this->writer->execute(new Command('ROLLBACK'));
        }
    }

    /**
     * @inheritdoc
     * @throws IocException
     */
    public function runMigrations(): array
    {
        $runMigrationClasses = $this->executedMigrations->getAll();
        $runMigrationClasses ??= [];
        // We want to reset the array keys, which is why we grab the values
        $migrationClassesToRun = array_values(array_diff($this->allMigrationClasses, $runMigrationClasses));
        $migrations            = $this->resolveManyMigrations($migrationClassesToRun);

        try {
            $this->writer->execute(new Command('TRANSACTION'));
            foreach ($migrations as $migration) {
                $migration->up();

                $this->executedMigrations->add(get_class($migration));
            }
            $this->writer->execute(new Command('COMMIT'));
        } catch (Exception $e) {
            // returning false will trigger a rollback
            $this->writer->execute(new Command('ROLLBACK'));
        }

        return $migrationClassesToRun;
    }

    /**
     * Resolves many migrations at once
     *
     * @param string[] $migrationClasses The list of migration classes to resolve
     *
     * @return IMigration[] The list of resolved migrations
     * @throws IocException
     */
    private function resolveManyMigrations(array $migrationClasses): array
    {
        $migrations = [];

        foreach ($migrationClasses as $migrationClass) {
            $migrations[] = $this->container->resolve($migrationClass);
        }

        return $migrations;
    }
}
