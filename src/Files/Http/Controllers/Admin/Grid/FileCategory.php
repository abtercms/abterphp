<?php

declare(strict_types=1);

namespace AbterPhp\Files\Http\Controllers\Admin\Grid;

use AbterPhp\Framework\Assets\AssetManager;
use AbterPhp\Framework\Http\Controllers\Admin\GridAbstract;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Session\FlashService;
use AbterPhp\Files\Service\RepoGrid\FileCategory as RepoGrid;
use Opulence\Routing\Urls\UrlGenerator;

class FileCategory extends GridAbstract
{
    const ENTITY_PLURAL = 'fileCategories';

    const ENTITY_TITLE_PLURAL = 'files:fileCategories';

    /** @var string */
    protected $resource = 'file_categories';

    /**
     * FileCategory constructor.
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
