<?php

declare(strict_types=1);

namespace AbterPhp\Files\Http\Controllers\Admin\Execute;

use AbterPhp\Framework\Http\Controllers\Admin\ExecuteAbstract;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Session\FlashService;
use AbterPhp\Files\Service\Execute\FileDownload as RepoService;
use Monolog\Logger;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;

class FileDownload extends ExecuteAbstract
{
    const ENTITY_SINGULAR = 'fileDownload';
    const ENTITY_PLURAL   = 'fileDownloads';

    const ENTITY_TITLE_SINGULAR = 'files:fileDownload';
    const ENTITY_TITLE_PLURAL   = 'files:fileDownloads';

    /**
     * FileDownload constructor.
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
