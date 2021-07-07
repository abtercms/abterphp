<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Http\Controllers\Api;

use AbterPhp\Admin\Http\Controllers\ApiAbstract;
use AbterPhp\Admin\Service\Execute\UserGroup as RepoService;
use Psr\Log\LoggerInterface;

class UserGroup extends ApiAbstract
{
    public const ENTITY_SINGULAR = 'userGroup';
    public const ENTITY_PLURAL   = 'userGroups';

    /**
     * UserGroup constructor.
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
