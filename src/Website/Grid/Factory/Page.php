<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Factory;

use AbterPhp\Framework\Grid\Action\Button;
use AbterPhp\Framework\Grid\Collection\Actions;
use AbterPhp\Framework\Grid\Factory\Base;
use AbterPhp\Framework\Grid\Factory\Grid;
use AbterPhp\Framework\Grid\Factory\Pagination as PaginationFactory;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Website\Constant\Routes;
use AbterPhp\Website\Grid\Factory\Table\Page as Table;
use AbterPhp\Website\Grid\Filters\Page as Filters;
use Opulence\Routing\Urls\UrlGenerator;

class Page extends Base
{
    const GROUP_ID         = 'page-id';
    const GROUP_IDENTIFIER = 'page-identifier';
    const GROUP_TITLE      = 'page-title';

    const GETTER_ID         = 'getId';
    const GETTER_IDENTIFIER = 'getIdentifier';
    const GETTER_TITLE      = 'getTitle';

    /**
     * Page constructor.
     *
     * @param UrlGenerator      $urlGenerator
     * @param PaginationFactory $paginationFactory
     * @param Table             $tableFactory
     * @param Grid              $gridFactory
     * @param ITranslator       $translator
     * @param Filters           $filters
     */
    public function __construct(
        UrlGenerator $urlGenerator,
        PaginationFactory $paginationFactory,
        Table $tableFactory,
        Grid $gridFactory,
        ITranslator $translator,
        Filters $filters
    ) {
        parent::__construct($urlGenerator, $paginationFactory, $tableFactory, $gridFactory, $translator, $filters);
    }

    /**
     * @return array
     */
    public function getGetters(): array
    {
        return [
            static::GROUP_ID         => static::GETTER_ID,
            static::GROUP_IDENTIFIER => static::GETTER_IDENTIFIER,
            static::GROUP_TITLE      => static::GETTER_TITLE,
        ];
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return [];
    }

    /**
     * @return Actions
     */
    protected function getRowActions(): Actions
    {
        $attributeCallbacks = $this->getAttributeCallbacks();

        $editAttributes   = [
            static::ATTRIBUTE_CLASS => Button::CLASS_PRIMARY,
            static::ATTRIBUTE_HREF  => Routes::ROUTE_PAGES_EDIT,
        ];
        $deleteAttributes = [
            static::ATTRIBUTE_CLASS => Button::CLASS_DANGER,
            static::ATTRIBUTE_HREF  => Routes::ROUTE_PAGES_DELETE,
        ];

        $cellActions   = new Actions();
        $cellActions[] = new Button(
            static::LABEL_EDIT,
            $editAttributes,
            $attributeCallbacks,
            $this->translator,
            Button::TAG_A
        );
        $cellActions[] = new Button(
            static::LABEL_DELETE,
            $deleteAttributes,
            $attributeCallbacks,
            $this->translator,
            Button::TAG_A
        );

        return $cellActions;
    }
}
