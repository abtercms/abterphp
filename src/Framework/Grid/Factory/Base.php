<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Factory;

use AbterPhp\Framework\Grid\Collection\Actions;
use AbterPhp\Framework\Grid\Collection\Filters;
use AbterPhp\Framework\Grid\Factory\Grid as GridFactory;
use AbterPhp\Framework\Grid\Factory\Pagination as PaginationFactory;
use AbterPhp\Framework\Grid\Factory\Table as TableFactory;
use AbterPhp\Framework\Grid\IGrid;
use AbterPhp\Framework\I18n\ITranslator;
use Opulence\Orm\IEntity;
use Opulence\Routing\Urls\UrlGenerator;

abstract class Base implements IBase
{
    const ATTRIBUTE_CLASS = 'class';
    const ATTRIBUTE_HREF  = 'href';

    const LABEL_EDIT   = 'framework:editItem';
    const LABEL_DELETE = 'framework:deleteItem';

    /** @var UrlGenerator */
    protected $urlGenerator;

    /** @var PaginationFactory */
    protected $paginationFactory;

    /** @var TableFactory */
    protected $tableFactory;

    /** @var GridFactory */
    protected $gridFactory;

    /** @var ITranslator */
    protected $translator;

    /** @var Filters */
    protected $filters;

    /** @var array */
    protected $pageSizeOptions = [];

    /** @var string */
    protected $url;

    /**
     * Base constructor.
     *
     * @param UrlGenerator      $urlGenerator
     * @param PaginationFactory $paginationFactory
     * @param Table             $tableFactory
     * @param Grid              $gridFactory
     * @param ITranslator       $translator
     * @param Filters|null      $filters
     */
    public function __construct(
        UrlGenerator $urlGenerator,
        PaginationFactory $paginationFactory,
        TableFactory $tableFactory,
        GridFactory $gridFactory,
        ITranslator $translator,
        Filters $filters = null
    ) {
        $this->urlGenerator      = $urlGenerator;
        $this->paginationFactory = $paginationFactory;
        $this->tableFactory      = $tableFactory;
        $this->gridFactory       = $gridFactory;
        $this->translator        = $translator;
        $this->filters           = $filters ?: new Filters([], $translator);
    }

    /**
     * @param Filters $filters
     *
     * @return IBase
     */
    public function setFilters(Filters $filters): IBase
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * @param array  $params
     * @param string $baseUrl
     *
     * @return IGrid
     */
    public function createGrid(array $params, string $baseUrl): IGrid
    {
        $this->filters->setParams($params);

        $filterUrl = $this->filters->getUrl($baseUrl);

        $rowActions  = $this->getRowActions();
        $gridActions = $this->getGridActions();
        $getters     = $this->getGetters();
        $headers     = $this->getHeaders();

        $pagination   = $this->paginationFactory->create($params, $filterUrl);
        $paginatedUrl = $pagination->getPageSizeUrl($filterUrl);

        $table = $this->tableFactory->create($getters, $rowActions, $headers, $params, $paginatedUrl);
        $sortedUrl = $table->getSortedUrl($paginatedUrl);
        $pagination->setSortedUrl($sortedUrl);

        $grid = $this->gridFactory->create($table, $pagination, $this->filters, $gridActions);

        return $grid;
    }

    abstract protected function getGetters(): array;

    abstract protected function getHeaders(): array;

    /**
     * @return Actions
     */
    protected function getRowActions(): Actions
    {
        $cellActions = new Actions();

        return $cellActions;
    }

    /**
     * @return Actions
     */
    protected function getGridActions(): Actions
    {
        $cellActions = new Actions();

        return $cellActions;
    }

    /**
     * @return \Closure
     */
    protected function getAttributeCallbacks(): array
    {
        $urlGenerator = $this->urlGenerator;

        $hrefClosure = function ($attribute, IEntity $entity) use ($urlGenerator) {
            return $urlGenerator->createFromName($attribute, $entity->getId());
        };

        $attributeCallbacks = [
            self::ATTRIBUTE_HREF => $hrefClosure,
        ];

        return $attributeCallbacks;
    }
}
