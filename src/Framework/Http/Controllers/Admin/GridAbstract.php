<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Http\Controllers\Admin;

use AbterPhp\Framework\Assets\AssetManager;
use AbterPhp\Framework\Grid\Factory\IBase as GridFactory;
use AbterPhp\Framework\Grid\Pagination\Options as PaginationOptions;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Session\FlashService;
use AbterPhp\Framework\Service\RepoGrid\IRepoGrid;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\Databases\Queries\FoundRows;
use AbterPhp\Framework\Orm\IGridRepo;
use Opulence\Http\Responses\Response;
use Opulence\Routing\Urls\UrlGenerator;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
abstract class GridAbstract extends AdminAbstract
{
    const ENTITY_PLURAL       = '';
    const ENTITY_TITLE_PLURAL = '';

    const VIEW_LIST = 'contents/backend/grid';

    const VAR_GRID       = 'grid';
    const VAR_CREATE_URL = 'createUrl';

    const TITLE_SHOW = 'framework:titleList';

    const URL_CREATE = '%s-create';

    /** @var IGridRepo */
    protected $gridRepo;

    /** @var FoundRows */
    protected $foundRows;

    /** @var GridFactory */
    protected $gridFactory;

    /** @var PaginationOptions */
    protected $paginationOptions;

    /** @var AssetManager */
    protected $assets;

    /** @var IRepoGrid */
    protected $repoGrid;

    /**
     * GridAbstract constructor.
     *
     * @param FlashService $flashService
     * @param ITranslator  $translator
     * @param UrlGenerator $urlGenerator
     * @param AssetManager $assets
     * @param IRepoGrid    $repoGrid
     */
    public function __construct(
        FlashService $flashService,
        ITranslator $translator,
        UrlGenerator $urlGenerator,
        AssetManager $assets,
        IRepoGrid $repoGrid
    ) {
        parent::__construct($flashService, $translator, $urlGenerator);

        $this->assets   = $assets;
        $this->repoGrid = $repoGrid;
    }

    /**
     * @return Response
     * @throws \Casbin\Exceptions\CasbinException
     * @throws \Throwable
     */
    public function show(): Response
    {
        $grid = $this->repoGrid->createAndPopulate($this->request->getQuery(), $this->getBaseUrl());

        $title = $this->translator->translate(static::TITLE_SHOW, static::ENTITY_TITLE_PLURAL);

        $this->view = $this->viewFactory->createView(static::VIEW_LIST);
        $this->view->setVar(static::VAR_GRID, $grid);
        $this->view->setVar(static::VAR_CREATE_URL, $this->getCreateUrl());

        $this->addCustomAssets();

        return $this->createResponse($title);
    }

    /**
     * @param IStringerEntity|null $entity
     */
    protected function addCustomAssets(?IStringerEntity $entity = null)
    {
        parent::addCustomAssets($entity);

        $footer = $this->getResourceName(static::RESOURCE_FOOTER);
        $this->assets->addJs($footer, '/admin-assets/js/hideable-container.js');
        $this->assets->addJs($footer, '/admin-assets/js/filters.js');
        $this->assets->addJs($footer, '/admin-assets/js/tooltips.js');
        $this->assets->addJs($footer, '/admin-assets/js/pagination.js');
    }

    /**
     * @return string
     * @throws \Opulence\Routing\Urls\URLException
     */
    protected function getBaseUrl(): string
    {
        return $this->urlGenerator->createFromName(static::ENTITY_PLURAL) . '?';
    }

    /**
     * @return string
     * @throws \Opulence\Routing\Urls\URLException
     */
    protected function getCreateUrl(): string
    {
        $urlName = strtolower(sprintf(static::URL_CREATE, static::ENTITY_PLURAL));
        $url     = $this->urlGenerator->createFromName($urlName);

        return $url;
    }
}
