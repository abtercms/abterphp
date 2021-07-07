<?php

declare(strict_types=1);

namespace AbterPhp\Files\Http\Controllers\Api;

use AbterPhp\Admin\Http\Controllers\ApiAbstract;
use AbterPhp\Files\Domain\Entities\File as Entity;
use AbterPhp\Files\Service\Execute\Api\File as RepoService;
use AbterPhp\Framework\Http\Service\Execute\IRepoService;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use Opulence\Http\Responses\Response;
use Psr\Log\LoggerInterface;

class File extends ApiAbstract
{
    protected const FILENAMESYSTEM_NAME_LENGTH = 6;

    public const ENTITY_SINGULAR = 'file';
    public const ENTITY_PLURAL   = 'files';

    /** @var Filesystem */
    protected Filesystem $filesystem;

    /** @var IRepoService */
    protected IRepoService $repoService;

    /**
     * File constructor.
     *
     * @param LoggerInterface $logger
     * @param RepoService     $repoService
     * @param string          $problemBaseUrl
     * @param Filesystem      $filesystem
     */
    public function __construct(
        LoggerInterface $logger,
        RepoService $repoService,
        string $problemBaseUrl,
        Filesystem $filesystem
    ) {
        parent::__construct($logger, $repoService, $problemBaseUrl);

        $this->filesystem = $filesystem;
    }

    /**
     * @param string $entityId
     *
     * @return Response
     * @throws FilesystemException
     */
    public function get(string $entityId): Response
    {
        try {
            $entity = $this->repoService->retrieveEntity($entityId);
        } catch (\Exception $e) {
            $msg = sprintf(static::LOG_MSG_GET_FAILURE, static::ENTITY_SINGULAR);

            return $this->handleException($msg, $e);
        }

        if (!($entity instanceof Entity)) {
            throw new \RuntimeException('Invalid entity');
        }

        if ($this->request->getQuery()->get('embed') === 'data') {
            $content = $this->filesystem->read($entity->getFilesystemName());

            $entity->setContent(base64_encode($content));
        }

        return $this->handleGetSuccess($entity);
    }

    /**
     * @return array
     * @throws FilesystemException
     */
    public function getSharedData(): array
    {
        $data = $this->request->getJsonBody();

        $path    = \bin2hex(\random_bytes(static::FILENAMESYSTEM_NAME_LENGTH));
        $content = base64_decode($data['data'], true);

        $this->filesystem->write($path, $content);

        $data['filesystem_name'] = $path;
        $data['public_name']     = $data['name'];

        return $data;
    }
}
