<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Navigation;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Html\Component;
use AbterPhp\Framework\Html\INode;

class Item extends Component
{
    const DEFAULT_TAG = Html5::TAG_LI;

    const INTENT_DROPDOWN = 'dropdown';

    /**
     * Item constructor.
     *
     * @param INode[]|INode|string|null $content
     * @param string[]                  $intents
     * @param array                     $attributes
     * @param string|null               $tag
     */
    public function __construct(
        $content = null,
        $intents = [],
        $attributes = [],
        ?string $tag = null
    ) {
        parent::__construct($content, $intents, $attributes, $tag);
    }
}
