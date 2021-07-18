<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Orm;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use QB\Generic\Statement\ISelect;

interface IGridRepo extends IRepository
{
    /**
     * @param int   $offset
     * @param int   $limit
     * @param array $sorting
     * @param array $filters
     *
     * @return IStringerEntity[]
     */
    public function getPage(int $offset, int $limit, array $sorting, array $filters): array;

    /**
     * @param array $filters
     *
     * @return int
     */
    public function getCount(array $filters): int;

    /**
     * @return array<string,string>
     */
    public function getDefaultSorting(): array;

    /**
     * @return ISelect
     */
    public function getGridQuery(): ISelect;
}
