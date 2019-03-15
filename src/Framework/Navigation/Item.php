<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Navigation;

use AbterPhp\Framework\Html\Component\Component;
use AbterPhp\Framework\Html\Component\IComponent;
use AbterPhp\Framework\I18n\ITranslator;

class Item extends Component
{
    const TAG_LI = 'li';

    const ATTRIBUTE_HREF = 'href';

    /**
     * Item constructor.
     *
     * @param string|IComponent $content
     * @param string|null       $tag
     * @param array             $attributes
     * @param ITranslator|null  $translator
     */
    public function __construct(
        $content = '',
        ?string $tag = self::TAG_LI,
        $attributes = [],
        ?ITranslator $translator = null
    ) {
        parent::__construct($content, $tag, $attributes, $translator);
    }
}
