<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Database\Migration;

use AbterPhp\Framework\Database\PDO\Writer;
use DateTime;
use Opulence\Databases\Migrations\IExecutedMigrationRepository;
use Opulence\Databases\Providers\Types\TypeMapper;
use QB\Generic\Expr\Expr;
use QB\Generic\QueryBuilder\IQueryBuilder;
use QB\Generic\Statement\Command;
use QB\Generic\Statement\ISelect;

class PdoMigrationRepository implements IExecutedMigrationRepository
{
    /** @var string The name of the default table */
    protected const DEFAULT_TABLE_NAME = 'executedmigrations';

    /** @var string The name of the table to read and write to */
    protected string $tableName = self::DEFAULT_TABLE_NAME;

    protected Writer $writer;

    protected IQueryBuilder $queryBuilder;

    protected TypeMapper $typeMapper;

    /**
     * ExecutedMigrationRepository constructor.
     *
     * @param Writer         $writer
     * @param IQueryBuilder $queryBuilder
     * @param TypeMapper     $typeMapper
     */
    public function __construct(Writer $writer, IQueryBuilder $queryBuilder, TypeMapper $typeMapper)
    {
        $this->writer       = $writer;
        $this->queryBuilder = $queryBuilder;
        $this->typeMapper   = $typeMapper;
    }

    /**
     * @param string $tableName
     */
    public function setTableName(string $tableName): void
    {
        $this->tableName = $tableName;
    }

    /**
     * Adds a migration that has been executed
     *
     * @param string $migrationClassName The class name of the migration that has been executed
     */
    public function add(string $migrationClassName): void
    {
        $this->createMigrationsTable();

        $values = [
            'migration' => $migrationClassName,
            'dateran'   => $this->typeMapper->toSqlTimestampWithTimeZone(new DateTime()),
        ];

        $query = $this->queryBuilder->insert()
            ->setInto($this->tableName)
            ->addValues($values);

        $this->writer->execute($query);
    }

    /**
     * Deletes a migration that has been executed
     *
     * @param string $migrationClassName The class name of the migration that has been executed
     */
    public function delete(string $migrationClassName): void
    {
        $this->createMigrationsTable();

        $query = $this->queryBuilder->delete()
            ->from($this->tableName)
            ->where(new Expr('migrations = ?', [$this->tableName]));

        $this->writer->execute($query);
    }

    /**
     * Gets all executed migration class names in descending order they were executed
     *
     * @return string[] The list of migration class names
     */
    public function getAll(): array
    {
        $this->createMigrationsTable();

        $query = $this->queryBuilder->select()
            ->from($this->tableName)
            ->columns('migration')
            ->orderBy('id', 'DESC');

        return $this->writer->fetchAll($query);
    }

    /**
     * Gets the last executed migrations
     *
     * @param int $number The number from the last migration to get
     *
     * @return string[] The last executed migration class names
     */
    public function getLast(int $number = 1): array
    {
        $this->createMigrationsTable();

        $select = $this->queryBuilder->select()
            ->from($this->tableName)
            ->columns('migration')
            ->orderBy('id', ISelect::DIRECTION_DESC)
            ->limit($number);

        return $this->writer->fetchColumn($select);
    }

    protected function createMigrationsTable()
    {
        $sql = match ($this->writer->getDialect()) {
            Writer::DIALECT_MYSQL => 'CREATE TABLE IF NOT EXISTS ' .
                $this->tableName .
                ' (id int not null auto_increment primary key, migration varchar(255), dateran timestamp NOT NULL);',
            Writer::DIALECT_PGSQL => 'CREATE TABLE IF NOT EXISTS ' .
                $this->tableName .
                ' (id serial primary key, migration text, dateran timestamp with time zone NOT NULL);',
            default => throw new \RuntimeException('SQL type not yet supported'),
        };

        $this->writer->execute(new Command($sql));
    }
}
