<?php

declare(strict_types=1);

namespace AbterPhp\Files\Http\Controllers\Admin\Execute;

use AbterPhp\Framework\Http\Controllers\Admin\ExecuteAbstract;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Session\FlashService;
use AbterPhp\Files\Service\Execute\Contact as RepoService;
use Monolog\Logger;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;

class File extends ExecuteAbstract
{
    const ENTITY_SINGULAR = 'file';
    const ENTITY_PLURAL   = 'files';

    const ENTITY_TITLE_SINGULAR = 'files:file';
    const ENTITY_TITLE_PLURAL   = 'files:files';

    /**
     * File constructor.
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
