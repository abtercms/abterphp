<?php

declare(strict_types=1);

namespace AbterPhp\Files\Http\Controllers\Api;

use AbterPhp\Admin\Http\Controllers\ApiAbstract;
use AbterPhp\Files\Service\Execute\FileDownload as RepoService;
use AbterPhp\Framework\Http\Service\Execute\IRepoService;
use Opulence\Http\Responses\Response;
use Opulence\Http\Responses\ResponseHeaders;
use Psr\Log\LoggerInterface;

class FileDownload extends ApiAbstract
{
    public const ENTITY_SINGULAR = 'fileDownload';
    public const ENTITY_PLURAL   = 'fileDownloads';

    /** @var IRepoService */
    protected IRepoService $repoService;

    /**
     * FileDownload constructor.
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

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param string $entityId
     *
     * @return Response
     */
    public function update(string $entityId): Response
    {
        $response = new Response();
        $response->setStatusCode(ResponseHeaders::HTTP_NOT_IMPLEMENTED);

        return $response;
    }
}
