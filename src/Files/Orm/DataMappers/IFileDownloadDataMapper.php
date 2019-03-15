<?php

declare(strict_types=1);

namespace AbterPhp\Files\Orm\DataMappers;

use AbterPhp\Files\Domain\Entities\FileDownload as Entity;
use Opulence\Orm\DataMappers\IDataMapper;

interface IFileDownloadDataMapper extends IDataMapper
{
    /**
     * @param int $fileId
     *
     * @return Entity[]
     */
    public function getByFileId(int $fileId): array;

    /**
     * @param int $userId
     *
     * @return Entity[]
     */
    public function getByUserId(int $userId): array;

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
