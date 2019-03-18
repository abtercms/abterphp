<?php

declare(strict_types=1);

namespace AbterPhp\Files\Grid\Factory;

use AbterPhp\Files\Constant\Routes;
use AbterPhp\Files\Domain\Entities\FileCategory as Entity;
use AbterPhp\Files\Grid\Factory\Table\FileCategory as Table;
use AbterPhp\Files\Grid\Filters\FileCategory as Filters;
use AbterPhp\Framework\Grid\Action\Button;
use AbterPhp\Framework\Grid\Collection\Actions;
use AbterPhp\Framework\Grid\Factory\Base;
use AbterPhp\Framework\Grid\Factory\Grid;
use AbterPhp\Framework\Grid\Factory\Pagination as PaginationFactory;
use AbterPhp\Framework\I18n\ITranslator;
use Opulence\Routing\Urls\UrlGenerator;

class FileCategory extends Base
{
    const GROUP_ID         = 'fileCategory-id';
    const GROUP_IDENTIFIER = 'fileCategory-identifier';
    const GROUP_NAME       = 'fileCategory-name';
    const GROUP_IS_PUBLIC  = 'fileCategory-is-public';

    const GETTER_ID         = 'getId';
    const GETTER_IDENTIFIER = 'getIdentifier';
    const GETTER_NAME       = 'getName';
    const GETTER_IS_PUBLIC  = 'isPublic';

    /**
     * FileCategory constructor.
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
            static::GROUP_NAME       => static::GETTER_NAME,
            static::GROUP_IS_PUBLIC  => [$this, 'getIsPublic'],
        ];
    }

    /**
     * @param Entity $entity
     *
     * @return string
     */
    public function getIsPublic(Entity $entity): string
    {
        $expr = $entity->isPublic() ? 'framework:yes' : 'framework:no';

        return $this->translator->translate($expr);
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
            static::ATTRIBUTE_HREF  => Routes::ROUTE_FILE_CATEGORIES_EDIT,
        ];

        $deleteAttributes = [
            static::ATTRIBUTE_CLASS => Button::CLASS_DANGER,
            static::ATTRIBUTE_HREF  => Routes::ROUTE_FILE_CATEGORIES_DELETE,
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
