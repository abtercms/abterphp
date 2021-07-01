<?php

declare(strict_types=1);

namespace AbterPhp\Website\Template\Builder\ContentList;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Template\ParsedTemplate;

class Base
{
    public const LIST_TAG    = 'list-tag';
    public const ITEM_TAG    = 'item-tag';
    public const LABEL_TAG   = 'label-tag';
    public const CONTENT_TAG = 'content-tag';
    public const IMAGE_TAG   = 'image-tag';

    public const LIST_CLASS    = 'list-class';
    public const ITEM_CLASS    = 'item-class';
    public const LABEL_CLASS   = 'label-class';
    public const CONTENT_CLASS = 'content-class';
    public const IMAGE_CLASS   = 'image-class';

    public const WITH_LABEL_OPTION  = 'with-label';
    public const WITH_IMAGES_OPTION = 'with-images';

    protected string $defaultListTag  = Html5::TAG_UL;
    protected string $defaultItemTag    = Html5::TAG_LI;
    protected string $defaultLabelTag   = '';
    protected string $defaultContentTag = '';
    protected string $defaultImageTag   = '';

    protected string $defaultListClass  = 'list-unknown';
    protected string $defaultItemClass    = 'list-item';
    protected string $defaultLabelClass   = 'list-item-label';
    protected string $defaultContentClass = 'list-item-content';
    protected string $defaultImageClass   = 'list-item-image';

    protected string $defaultWithLabel = '0';
    protected string $defaultWithImage = '0';

    /**
     * @param ParsedTemplate|null $template
     *
     * @return array<string,string>
     */
    protected function getWrapperTags(?ParsedTemplate $template = null): array
    {
        if (!$template) {
            return [
                IContentList::LIST_TAG    => $this->defaultListTag,
                IContentList::ITEM_TAG    => $this->defaultItemTag,
                IContentList::LABEL_TAG   => $this->defaultLabelTag,
                IContentList::CONTENT_TAG => $this->defaultContentTag,
                IContentList::IMAGE_TAG   => $this->defaultImageTag,
            ];
        }

        $listTag    = $template->getAttributeValue(IContentList::LIST_TAG, $this->defaultListTag);
        $itemTag    = $template->getAttributeValue(IContentList::ITEM_TAG, $this->defaultItemTag);
        $labelTag   = $template->getAttributeValue(IContentList::LABEL_TAG, $this->defaultLabelTag);
        $contentTag = $template->getAttributeValue(IContentList::CONTENT_TAG, $this->defaultContentTag);
        $imageTag   = $template->getAttributeValue(IContentList::IMAGE_TAG, $this->defaultImageTag);

        return [
            IContentList::LIST_TAG    => $listTag,
            IContentList::ITEM_TAG    => $itemTag,
            IContentList::LABEL_TAG   => $labelTag,
            IContentList::CONTENT_TAG => $contentTag,
            IContentList::IMAGE_TAG   => $imageTag,
        ];
    }

    /**
     * @param ParsedTemplate|null $template
     *
     * @return array<string,string>
     */
    protected function getWrapperClasses(?ParsedTemplate $template = null): array
    {
        if (!$template) {
            return [
                IContentList::LIST_CLASS    => $this->defaultListClass,
                IContentList::ITEM_CLASS    => $this->defaultItemClass,
                IContentList::LABEL_CLASS   => $this->defaultLabelClass,
                IContentList::CONTENT_CLASS => $this->defaultContentClass,
                IContentList::IMAGE_CLASS   => $this->defaultImageClass,
            ];
        }

        $listClass    = $template->getAttributeValue(IContentList::LIST_CLASS, $this->defaultListClass);
        $itemClass    = $template->getAttributeValue(IContentList::ITEM_CLASS, $this->defaultItemClass);
        $labelClass   = $template->getAttributeValue(IContentList::LABEL_CLASS, $this->defaultLabelClass);
        $contentClass = $template->getAttributeValue(IContentList::CONTENT_CLASS, $this->defaultContentClass);
        $imageClass   = $template->getAttributeValue(IContentList::IMAGE_CLASS, $this->defaultImageClass);

        return [
            IContentList::LIST_CLASS    => $listClass,
            IContentList::ITEM_CLASS    => $itemClass,
            IContentList::LABEL_CLASS   => $labelClass,
            IContentList::CONTENT_CLASS => $contentClass,
            IContentList::IMAGE_CLASS   => $imageClass,
        ];
    }

    /**
     * @param ParsedTemplate|null $template
     *
     * @return array
     */
    protected function getOptions(?ParsedTemplate $template = null): array
    {
        if (!$template) {
            return [
                IContentList::WITH_LABEL_OPTION  => (bool)$this->defaultWithLabel,
                IContentList::WITH_IMAGES_OPTION => (bool)$this->defaultWithImage,
            ];
        }

        $listClass = $template->getAttributeValue(IContentList::WITH_LABEL_OPTION, $this->defaultWithLabel);
        $itemClass = $template->getAttributeValue(IContentList::WITH_IMAGES_OPTION, $this->defaultWithImage);

        return [
            IContentList::WITH_LABEL_OPTION  => (bool)$listClass,
            IContentList::WITH_IMAGES_OPTION => (bool)$itemClass,
        ];
    }
}
