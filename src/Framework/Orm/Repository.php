<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Orm;

use AbterPhp\Framework\Database\PDO\Writer;
use Opulence\Orm\IEntity;
use QB\Generic\Expr\Expr;
use QB\Generic\QueryBuilder\IQueryBuilder;
use QB\Generic\Statement\IDelete;
use QB\Generic\Statement\ISelect;
use QB\Generic\Statement\IUpdate;
use QB\Generic\Statement\IWhereStatement;

abstract class Repository implements IRepository
{
    protected const COLUMN_ID         = 'id';
    protected const COLUMN_DELETED_AT = 'deleted_at';

    protected const DEFAULT_LIMIT = 1000;

    protected Writer $writer;

    /** @var IQueryBuilder */
    protected $queryBuilder;

    protected string $tableName;

    protected string $idColumn = self::COLUMN_ID;
    protected ?string $deletedAtColumn = null;

    protected int $limit = self::DEFAULT_LIMIT;

    /**
     * Repository constructor.
     *
     * @param Writer        $writer
     * @param IQueryBuilder $queryBuilder
     */
    public function __construct(Writer $writer, IQueryBuilder $queryBuilder)
    {
        $this->writer       = $writer;
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @param string $tableName
     *
     * @return $this
     */
    public function setTableName(string $tableName): self
    {
        $this->tableName = $tableName;

        return $this;
    }

    /**
     * @param int $limit
     */
    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @param IEntity $entity
     */
    public function add(IEntity $entity)
    {
        $data   = $this->getData($entity);
        $keys   = array_keys($data);
        $values = array_values($data);

        $insert = $this->queryBuilder
            ->insert()
            ->into($this->tableName)
            ->columns(...$keys)
            ->values(...$values);

        $this->writer->execute($insert);
    }

    /**
     * @param IEntity $entity
     */
    public function update(IEntity $entity)
    {
        $update = $this->queryBuilder
            ->update($this->tableName)
            ->values($this->getData($entity));

        $update = $this->addWhereByEntity($update, $entity);

        $this->writer->execute($update);
    }

    /**
     * @param IEntity $entity
     */
    public function delete(IEntity $entity)
    {
        if ($this->deletedAtColumn === null) {
            $delete = $this->queryBuilder->delete()
                ->from($this->tableName);
        } else {
            $delete = $this->queryBuilder->update($this->tableName)
                ->values([$this->deletedAtColumn => 'NOW()']);
        }

        $delete = $this->addWhereByEntity($delete, $entity);

        $this->writer->execute($delete);
    }

    /**
     * @return IEntity[]
     */
    public function getAll(): array
    {
        $select = $this->getBaseQuery();

        $rows = $this->writer->fetchAll($select);

        return $this->createCollection($rows);
    }

    /**
     * Gets the entity with the input Id
     *
     * @param int|string $id The Id of the entity we're searching for
     *
     * @return IEntity|null The entity with the input Id
     */
    public function getById($id): ?IEntity
    {
        return $this->getOne([$this->idColumn => $id]);
    }

    /**
     * @param array $where
     *
     * @return IEntity|null
     */
    protected function getOne(array $where): ?IEntity
    {
        $select = $this->getBaseQuery();
        foreach ($where as $k => $v) {
            if ($v instanceof Expr) {
                $select = $select->where($v);
            } else {
                $select = $select->where(new Expr($k . ' = ?', [$v]));
            }
        }
        if ($this->deletedAtColumn !== null) {
            $select = $select->where($this->deletedAtColumn . ' IS NULL');
        }
        $select = $select->limit(1);

        $row = $this->writer->fetch($select);

        if (empty($row)) {
            return null;
        }

        return $this->createEntity($row);
    }

    /**
     * @param IWhereStatement $select
     * @param IEntity         $entity
     *
     * @return IWhereStatement $select
     */
    protected function addWhereByEntity(IWhereStatement $select, IEntity $entity): ISelect|IUpdate|IDelete
    {
        $select = $select->where(new Expr($this->idColumn . ' = ?', [$entity->getId()]));

        if ($this->deletedAtColumn !== null) {
            $select = $select->where($this->deletedAtColumn . ' IS NULL');
        }

        return $select;
    }

    /**
     * @param IEntity $entity
     *
     * @return array
     */
    protected function getData(IEntity $entity): array
    {
        return $entity->toData();
    }

    /**
     * @return array|null
     */
    protected function getColumns(): ?array
    {
        return null;
    }

    /**
     * @return string
     */
    protected function getColumnsStr(): string
    {
        return '*';
    }

    /**
     * @param array<string,mixed> $rows
     *
     * @return IEntity[]
     */
    public function createCollection(array $rows): array
    {
        $collection = [];
        foreach ($rows as $row) {
            $collection[] = $this->createEntity($row);
        }

        return $collection;
    }

    /**
     * @return ISelect
     */
    protected function getBaseQuery(): ISelect
    {
        $columns = $this->getColumns() ?? ['*'];

        return $this->queryBuilder->select()
            ->from($this->tableName)
            ->columns(...$columns)
            ->limit($this->limit);
    }

    /**
     * @param array $row
     *
     * @return IEntity
     */
    abstract public function createEntity(array $row): IEntity;
}