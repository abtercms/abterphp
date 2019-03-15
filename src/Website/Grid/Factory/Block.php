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
use AbterPhp\Website\Grid\Factory\Table\Block as Table;
use AbterPhp\Website\Grid\Filters\Block as Filters;
use Opulence\Routing\Urls\UrlGenerator;

class Block extends Base
{
    const GROUP_ID         = 'block-id';
    const GROUP_IDENTIFIER = 'block-identifier';
    const GROUP_TITLE      = 'block-title';

    const GETTER_ID         = 'getId';
    const GETTER_IDENTIFIER = 'getIdentifier';
    const GETTER_TITLE      = 'getTitle';

    /**
     * Block constructor.
     *
     * @param UrlGenerator $urlGenerator
     * @param PaginationFactory $paginationFactory
     * @param Table        $tableFactory
     * @param Grid         $gridFactory
     * @param ITranslator  $translator
     * @param Filters      $blockFilters
     */
    public function __construct(
        UrlGenerator $urlGenerator,
        PaginationFactory $paginationFactory,
        Table $tableFactory,
        Grid $gridFactory,
        ITranslator $translator,
        Filters $blockFilters
    ) {
        parent::__construct($urlGenerator, $paginationFactory, $tableFactory, $gridFactory, $translator, $blockFilters);
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
            static::ATTRIBUTE_HREF  => Routes::ROUTE_BLOCKS_EDIT,
        ];
        $deleteAttributes = [
            static::ATTRIBUTE_CLASS => Button::CLASS_DANGER,
            static::ATTRIBUTE_HREF  => Routes::ROUTE_BLOCKS_DELETE,
        ];

        $cellActions   = new Actions();
        $cellActions[] = new Button(
            static::LABEL_EDIT,
            Button::TAG_A,
            $editAttributes,
            $attributeCallbacks,
            $this->translator
        );
        $cellActions[] = new Button(
            static::LABEL_DELETE,
            Button::TAG_A,
            $deleteAttributes,
            $attributeCallbacks,
            $this->translator
        );

        return $cellActions;
    }
}
