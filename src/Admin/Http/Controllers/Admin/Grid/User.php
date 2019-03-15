<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Http\Controllers\Admin\Grid;

use AbterPhp\Framework\Assets\AssetManager;
use AbterPhp\Framework\Http\Controllers\Admin\GridAbstract;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Session\FlashService;
use AbterPhp\Admin\Service\RepoGrid\User as RepoGrid;
use Opulence\Routing\Urls\UrlGenerator;

class User extends GridAbstract
{
    const ENTITY_PLURAL = 'users';

    const ENTITY_TITLE_PLURAL = 'admin:users';

    /** @var string */
    protected $resource = 'users';

    /**
     * User constructor.
     *
     * @param FlashService $flashService
     * @param ITranslator  $translator
     * @param UrlGenerator $urlGenerator
     * @param AssetManager $assets
     * @param RepoGrid     $repoGrid
     */
    public function __construct(
        FlashService $flashService,
        ITranslator $translator,
        UrlGenerator $urlGenerator,
        AssetManager $assets,
        RepoGrid $repoGrid
    ) {
        parent::__construct(
            $flashService,
            $translator,
            $urlGenerator,
            $assets,
            $repoGrid
        );
    }
}
