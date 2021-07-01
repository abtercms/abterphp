<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Orm;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;

interface IGridRepo
{
    /**
     * @param int   $limitFrom
     * @param int   $pageSize
     * @param array $orders
     * @param array $filters
     * @param array $params
     *
     * @return IStringerEntity[]
     */
    public function getPage(int $limitFrom, int $pageSize, array $orders, array $filters, array $params): array;
}
