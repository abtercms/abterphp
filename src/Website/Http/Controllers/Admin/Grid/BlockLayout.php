<?php

declare(strict_types=1);

namespace AbterPhp\Website\Http\Controllers\Admin\Grid;

use AbterPhp\Framework\Assets\AssetManager;
use AbterPhp\Framework\Http\Controllers\Admin\GridAbstract;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Session\FlashService;
use AbterPhp\Website\Service\RepoGrid\BlockLayout as RepoGrid;
use Opulence\Routing\Urls\UrlGenerator;

class BlockLayout extends GridAbstract
{
    const ENTITY_PLURAL = 'blockLayouts';

    const ENTITY_TITLE_PLURAL = 'pages:blockLayouts';

    /** @var string */
    protected $resource = 'block_layouts';

    /**
     * BlockLayout constructor.
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
