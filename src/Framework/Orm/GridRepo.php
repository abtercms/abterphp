<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Orm;

use Opulence\Orm\IEntity;
use QB\Generic\Expr\Expr;
use QB\Generic\IQueryPart;
use QB\Generic\Statement\ISelect;

abstract class GridRepo extends Repository implements IGridRepo
{
    /**
     * @param int      $offset
     * @param int      $limit
     * @param string[] $sorting
     * @param array    $filters
     *
     * @return IEntity[]
     */
    public function getPage(int $offset, int $limit, array $sorting, array $filters): array
    {
        $select = $this->getGridQuery();

        foreach ($filters as $k => $v) {
            if ($v instanceof IQueryPart) {
                $select->where($v);
            } else {
                $select = $select->where(new Expr($k . ' = ?', [$v]));
            }
        }
        if ($this->deletedAtColumn !== null) {
            $select = $select->where($this->deletedAtColumn . ' IS NULL');
        }
        $select = $select->limit($limit)->offset($offset);

        $sorting = $sorting ?? $this->getDefaultSorting();
        foreach ($sorting as $column => $dir) {
            $select = $select->orderBy($column, $dir);
        }

        $rows = $this->writer->fetchAll($select);

        return $this->createCollection($rows);
    }

    /**
     * @param array $filters
     *
     * @return int
     */
    public function getCount(array $filters): int
    {
        $select = $this->getGridQuery();

        foreach ($filters as $k => $v) {
            if ($v instanceof IQueryPart) {
                $select->where($v);
            } else {
                $select = $select->where(new Expr($k . ' = ?', [$v]));
            }
        }
        if ($this->deletedAtColumn) {
            $select = $select->where($this->deletedAtColumn . ' IS NULL');
        }

        return $this->writer->fetchColumn($select);
    }

    /**
     * @return ISelect
     */
    public function getGridQuery(): ISelect
    {
        return $this->getBaseQuery();
    }

    /**
     * @return array<string,string>
     */
    abstract public function getDefaultSorting(): array;
}