<?php

declare(strict_types=1);

namespace AbterPhp\Files\Http\Controllers\Api;

use AbterPhp\Admin\Http\Controllers\ApiAbstract;
use AbterPhp\Files\Service\Execute\FileCategory as RepoService;
use AbterPhp\Framework\Databases\Queries\FoundRows;
use AbterPhp\Framework\Http\Service\Execute\IRepoService;
use Psr\Log\LoggerInterface;

class FileCategory extends ApiAbstract
{
    public const ENTITY_SINGULAR = 'fileCategory';
    public const ENTITY_PLURAL   = 'fileCategories';

    /** @var IRepoService */
    protected IRepoService $repoService;

    /**
     * FileCategory constructor.
     *
     * @param LoggerInterface $logger
     * @param RepoService     $repoService
     * @param FoundRows       $foundRows
     * @param string          $problemBaseUrl
     */
    public function __construct(
        LoggerInterface $logger,
        RepoService $repoService,
        FoundRows $foundRows,
        string $problemBaseUrl
    ) {
        parent::__construct($logger, $repoService, $foundRows, $problemBaseUrl);
    }

    /**
     * @return array
     */
    public function getSharedData(): array
    {
        $data = $this->request->getJsonBody();

        if (array_key_exists('password', $data)) {
            $data['password_repeated'] = $data['password'];
        }

        return $data;
    }
}
