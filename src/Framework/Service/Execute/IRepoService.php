<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Service\Execute;

use Opulence\Http\Requests\UploadedFile;

interface IRepoService
{
    /**
     * @param array $postData
     *
     * @return array
     */
    public function validateForm(array $postData): array;

    /**
     * @param string[]       $postData
     * @param UploadedFile[] $fileData
     *
     * @return int id of the created entity
     */
    public function create(array $postData, array $fileData): int;

    /**
     * @param int            $entityId
     * @param string[]       $postData
     * @param UploadedFile[] $fileData
     *
     * @return bool
     */
    public function update(int $entityId, array $postData, array $fileData): bool;

    /**
     * @param int $entityId
     *
     * @return bool
     */
    public function delete(int $entityId): bool;
}
