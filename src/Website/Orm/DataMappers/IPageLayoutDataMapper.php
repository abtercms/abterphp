<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm\DataMappers;

use AbterPhp\Website\Domain\Entities\PageLayout as Entity;
use Opulence\Orm\DataMappers\IDataMapper;

interface IPageLayoutDataMapper extends IDataMapper
{
    /**
     * @param string $identifier
     *
     * @return Entity|null
     */
    public function getByIdentifier(string $identifier): ?Entity;

    /**
     * @param int      $limitFrom
     * @param int      $pageSize
     * @param string[] $orders
     * @param array    $filters
     * @param array    $params
     *
     * @return Entity[]
     */
    public function getPage(int $limitFrom, int $pageSize, array $orders, array $filters, array $params): array;
}
