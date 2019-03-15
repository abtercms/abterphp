<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Http\Controllers\Admin\Execute;

use AbterPhp\Framework\Http\Controllers\Admin\ExecuteAbstract;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Session\FlashService;
use AbterPhp\Admin\Service\Execute\User as RepoService;
use Monolog\Logger;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;

class User extends ExecuteAbstract
{
    const ENTITY_SINGULAR = 'user';
    const ENTITY_PLURAL   = 'users';

    const ENTITY_TITLE_SINGULAR = 'admin:user';
    const ENTITY_TITLE_PLURAL   = 'admin:users';

    /**
     * User constructor.
     *
     * @param FlashService $flashService
     * @param ITranslator  $translator
     * @param UrlGenerator $urlGenerator
     * @param RepoService  $repoService
     * @param ISession     $session
     * @param Logger       $logger
     */
    public function __construct(
        FlashService $flashService,
        ITranslator $translator,
        UrlGenerator $urlGenerator,
        RepoService $repoService,
        ISession $session,
        Logger $logger
    ) {
        parent::__construct(
            $flashService,
            $translator,
            $urlGenerator,
            $repoService,
            $session,
            $logger
        );
    }
}
