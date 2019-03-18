<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Navigation;

use AbterPhp\Framework\Html\Component\Tag;
use AbterPhp\Framework\Html\Component\IComponent;
use AbterPhp\Framework\I18n\ITranslator;

class Item extends Tag
{
    const DEFAULT_TAG = self::TAG_LI;

    const TAG_LI = 'li';

    const ATTRIBUTE_HREF = 'href';

    /**
     * Item constructor.
     *
     * @param string|IComponent $content
     * @param array             $attributes
     * @param ITranslator|null  $translator
     * @param string|null       $tag
     */
    public function __construct(
        $content = '',
        $attributes = [],
        ?ITranslator $translator = null,
        ?string $tag = null
    ) {
        parent::__construct($content, $attributes, $translator, $tag);
    }
}
