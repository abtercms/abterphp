<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Orm;

use AbterPhp\Admin\Domain\Entities\AdminResource as Entity;
use AbterPhp\Framework\Database\PDO\Writer;
use AbterPhp\Framework\Form\Element\Select;
use DateTime;
use Opulence\Databases\Providers\Types\TypeMapper;
use Opulence\Orm\IEntity;
use QB\Generic\Expr\Expr;
use QB\Generic\QueryBuilder\IQueryBuilder;
use QB\Generic\Statement\ISelect;

abstract class Repository implements IRepository
{
    protected const COLUMN_ID         = 'id';
    protected const COLUMN_DELETED_AT = 'deleted_at';

    protected const DEFAULT_LIMIT = 1000;

    protected Writer $writer;

    protected IQueryBuilder $queryBuilder;

    protected TypeMapper $typeMapper;

    protected string $tableName;

    protected string $idColumn = self::COLUMN_ID;
    protected ?string $deletedAtColumn = null;

    protected int $limit = self::DEFAULT_LIMIT;

    /**
     * Repository constructor.
     *
     * @param Writer        $writer
     * @param IQueryBuilder $queryBuilder
     * @param TypeMapper    $typeMapper
     */
    public function __construct(Writer $writer, IQueryBuilder $queryBuilder, TypeMapper $typeMapper)
    {
        $this->writer       = $writer;
        $this->queryBuilder = $queryBuilder;
        $this->typeMapper   = $typeMapper;
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
        assert($entity instanceof Entity, new \InvalidArgumentException());

        $insert = $this->queryBuilder
            ->insert()
            ->setInto($this->tableName)
            ->addValues($this->getData($entity));

        $this->writer->execute($insert);
    }

    /**
     * @param IEntity $entity
     */
    public function update(IEntity $entity)
    {
        assert($entity instanceof Entity, new \InvalidArgumentException());

        $update = $this->queryBuilder
            ->update($this->tableName)
            ->setValues($this->getData($entity));

        $update = $this->addWhereByEntity($entity, $update->where())->end();

        $this->writer->exec($update->getSql());
    }

    /**
     * @param IEntity $entity
     */
    public function delete(IEntity $entity)
    {
        assert($entity instanceof Entity, new \InvalidArgumentException());

        if ($this->deletedAtColumn) {
            $delete = $this->queryBuilder->delete()->addFrom($this->tableName);
        } else {
            $delete = $this->queryBuilder->update($this->tableName)
                ->addWhere(new Expr($this->deletedAtColumn . ' = NOW()'));
        }

        $delete = $this->addWhereByEntity($entity, $delete->where())->end();

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
        $this->getOne([$this->idColumn => $id]);
    }

    /**
     * @param array $where
     *
     * @return IEntity|null
     */
    protected function getOne(array $where): ?IEntity
    {
        $select = $this->getBaseQuery()->where();
        foreach ($where as $k => $v) {
            $select = $select->equals($k, $v);
        }
        if ($this->deletedAtColumn) {
            $select->equals($this->deletedAtColumn, null);
        }
        $select = $select->end()->limit(0, 1);

        $row = $this->writer->fetch($select->getSql());

        if (empty($row)) {
            return null;
        }

        return $this->createEntity($row);
    }

    /**
     * @param int      $limitFrom
     * @param int      $pageSize
     * @param string[] $orders
     * @param array    $filters
     * @param array    $params
     *
     * @return IEntity[]
     */
    public function getPage(int $limitFrom, int $pageSize, array $orders, array $filters, array $params): array
    {
        $select = $this->getBaseQuery();

        $select = $select->where();
        foreach ($filters as $k => $v) {
            $select = $select->equals($k, $v);
        }
        $select = $select->end();
        $select = $select->limit($limitFrom, $pageSize);

        $rows = $this->writer->fetch($select->getSql());

        return $this->createCollection($rows);
    }

    /**
     * @param IEntity $entity
     * @param Where   $where
     *
     * @return Where
     */
    protected function addWhereByEntity(IEntity $entity, Where $where): Where
    {
        $where = $where->equals($this->idColumn, $entity->getId());

        if ($this->deletedAtColumn) {
            $where = $where->equals($this->deletedAtColumn, null);
        }

        return $where;
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
        return $this->queryBuilder->select()
            ->addFrom($this->tableName)
            ->addColumns(...$this->getColumns())
            ->setLimit($this->limit);
    }

    /**
     * @param array $row
     *
     * @return IEntity
     */
    abstract public function createEntity(array $row): IEntity;
}