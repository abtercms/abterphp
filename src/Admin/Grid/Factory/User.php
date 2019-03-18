<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Grid\Factory;

use AbterPhp\Admin\Constant\Routes;
use AbterPhp\Framework\Grid\Action\Button;
use AbterPhp\Framework\Grid\Collection\Actions;
use AbterPhp\Framework\Grid\Factory\Base;
use AbterPhp\Framework\Grid\Factory\Grid;
use AbterPhp\Framework\Grid\Factory\Pagination as PaginationFactory;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Admin\Grid\Factory\Table\User as Table;
use AbterPhp\Admin\Grid\Filters\User as Filters;
use Opulence\Routing\Urls\UrlGenerator;

class User extends Base
{
    const GROUP_ID       = 'user-id';
    const GROUP_USERNAME = 'user-username';
    const GROUP_EMAIL    = 'user-email';

    const GETTER_ID       = 'getId';
    const GETTER_USERNAME = 'getUsername';
    const GETTER_EMAIL    = 'getEmail';

    /**
     * User constructor.
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
            static::GROUP_ID       => static::GETTER_ID,
            static::GROUP_USERNAME => static::GETTER_USERNAME,
            static::GROUP_EMAIL    => static::GETTER_EMAIL,
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

        $editAttributes = [
            static::ATTRIBUTE_CLASS => Button::CLASS_PRIMARY,
            static::ATTRIBUTE_HREF  => Routes::ROUTE_USERS_EDIT,
        ];

        $deleteAttributes = [
            static::ATTRIBUTE_CLASS => Button::CLASS_DANGER,
            static::ATTRIBUTE_HREF  => Routes::ROUTE_USERS_DELETE,
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
