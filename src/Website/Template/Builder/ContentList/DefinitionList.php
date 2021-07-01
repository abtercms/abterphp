<?php

declare(strict_types=1);

namespace AbterPhp\Website\Template\Builder\ContentList;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Template\IBuilder;
use AbterPhp\Website\Domain\Entities\ContentList as Entity;
use AbterPhp\Website\Domain\Entities\ContentListItem as Item;

class DefinitionList extends Base implements IBuilder
{
    use ItemTrait;

    protected const IDENTIFIER = 'definition-list';

    protected string $defaultListTag  = Html5::TAG_DL;
    protected string $defaultItemTag    = '';
    protected string $defaultLabelTag   = Html5::TAG_DT;
    protected string $defaultContentTag = Html5::TAG_DD;

    protected string $defaultListClass = 'definition-list';

    protected string $defaultWithLabel = '1';

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return static::IDENTIFIER;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param Item                 $item
     * @param Entity               $list
     * @param array<string,string> $tags
     * @param array<string,string> $classes
     * @param array<string,string> $options
     *
     * @return string
     */
    protected function buildImage(Item $item, Entity $list, array $tags, array $classes, array $options): string
    {
        return '';
    }
}
