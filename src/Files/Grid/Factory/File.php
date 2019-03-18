<?php

declare(strict_types=1);

namespace AbterPhp\Files\Grid\Factory;

use AbterPhp\Files\Constant\Routes;
use AbterPhp\Files\Domain\Entities\File as Entity;
use AbterPhp\Files\Grid\Factory\Table\File as Table;
use AbterPhp\Files\Grid\Filters\File as Filters;
use AbterPhp\Framework\Grid\Action\Button;
use AbterPhp\Framework\Grid\Collection\Actions;
use AbterPhp\Framework\Grid\Factory\Base;
use AbterPhp\Framework\Grid\Factory\Grid;
use AbterPhp\Framework\Grid\Factory\Pagination as PaginationFactory;
use AbterPhp\Framework\Helper\DateHelper;
use AbterPhp\Framework\I18n\ITranslator;
use Opulence\Routing\Urls\UrlGenerator;

class File extends Base
{
    const GROUP_ID          = 'file-id';
    const GROUP_FILENAME    = 'file-filename';
    const GROUP_CATEGORY    = 'file-category';
    const GROUP_DESCRIPTION = 'file-description';
    const GROUP_UPLOADED_AT = 'file-uploaded-at';

    const GETTER_ID          = 'getId';
    const GETTER_PUBLIC_NAME = 'getPublicName';
    const GETTER_CATEGORY    = 'getCategory';
    const GETTER_DESCRIPTION = 'getDescription';

    const LABEL_DOWNLOAD = 'files:download';

    /**
     * File constructor.
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
            static::GROUP_ID          => static::GETTER_ID,
            static::GROUP_FILENAME    => static::GETTER_PUBLIC_NAME,
            static::GROUP_CATEGORY    => static::GETTER_CATEGORY,
            static::GROUP_DESCRIPTION => static::GETTER_DESCRIPTION,
            /** @see File::getUploadedAt */
            static::GROUP_UPLOADED_AT => [$this, 'getUploadedAt'],
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
     * @param Entity $entity
     *
     * @return string
     */
    public function getUploadedAt(Entity $entity): string
    {
        return DateHelper::format($entity->getUploadedAt());
    }

    /**
     * @return Actions
     */
    protected function getRowActions(): Actions
    {
        $attributeCallbacks = $this->getAttributeCallbacks();
        $downloadCallbacks  = $this->getDownloadCallbacks();

        $downloadAttributes = [
            static::ATTRIBUTE_CLASS => Button::CLASS_WARNING,
            static::ATTRIBUTE_HREF  => Routes::ROUTE_FILES_DOWNLOAD,
        ];
        $editAttributes     = [
            static::ATTRIBUTE_CLASS => Button::CLASS_PRIMARY,
            static::ATTRIBUTE_HREF  => Routes::ROUTE_FILES_EDIT,
        ];
        $deleteAttributes   = [
            static::ATTRIBUTE_CLASS => Button::CLASS_DANGER,
            static::ATTRIBUTE_HREF  => Routes::ROUTE_FILES_DELETE,
        ];

        $cellActions   = new Actions();
        $cellActions[] = new Button(
            static::LABEL_DOWNLOAD,
            $downloadAttributes,
            $downloadCallbacks,
            $this->translator,
            Button::TAG_A
        );
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

    /**
     * @return \Closure
     */
    protected function getAttributeCallbacks(): array
    {
        $attributeCallbacks = parent::getAttributeCallbacks();

        $attributeCallbacks[self::ATTRIBUTE_CLASS] = function ($attribute, Entity $entity) {
            return $entity->isWritable() ? $attribute : Button::CLASS_HIDDEN;
        };

        return $attributeCallbacks;
    }

    /**
     * @return \Closure
     */
    protected function getDownloadCallbacks(): array
    {
        $urlGenerator = $this->urlGenerator;

        $closure = function ($attribute, Entity $entity) use ($urlGenerator) {
            try {
                return $urlGenerator->createFromName($attribute, $entity->getFilesystemName());
            } catch (\Exception $e) {
                return '';
            }
        };

        $attributeCallbacks = [
            self::ATTRIBUTE_HREF => $closure,
        ];

        return $attributeCallbacks;
    }
}
