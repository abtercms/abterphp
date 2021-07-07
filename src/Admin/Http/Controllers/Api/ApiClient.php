<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Http\Controllers\Api;

use AbterPhp\Admin\Http\Controllers\ApiAbstract;
use AbterPhp\Admin\Service\Execute\ApiClient as RepoService;
use Psr\Log\LoggerInterface;

class ApiClient extends ApiAbstract
{
    public const ENTITY_SINGULAR = 'apiClient';
    public const ENTITY_PLURAL   = 'apiClients';

    /**
     * ApiClient constructor.
     *
     * @param LoggerInterface $logger
     * @param RepoService     $repoService
     * @param string          $problemBaseUrl
     */
    public function __construct(
        LoggerInterface $logger,
        RepoService $repoService,
        string $problemBaseUrl
    ) {
        parent::__construct($logger, $repoService, $problemBaseUrl);
    }
}
