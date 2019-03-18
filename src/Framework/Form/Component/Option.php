<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Component;

use AbterPhp\Framework\Html\Component\IComponent;
use AbterPhp\Framework\Html\Component\Tag;
use AbterPhp\Framework\I18n\ITranslator;

class Option extends Tag
{
    const DEFAULT_TAG = self::TAG_OPTION;

    const TAG_OPTION = 'option';

    const ATTRIBUTE_VALUE    = 'value';
    const ATTRIBUTE_SELECTED = 'selected';

    /**
     * Tag constructor.
     *
     * @param string            $value
     * @param string|IComponent $content
     * @param bool              $isSelected
     * @param string[]          $attributes
     * @param ITranslator|null  $translator
     * @param string|null       $tag
     */
    public function __construct(
        string $value,
        $content,
        bool $isSelected = false,
        array $attributes = [],
        ?ITranslator $translator = null,
        ?string $tag = null
    ) {
        $this->attributes[static::ATTRIBUTE_VALUE] = $value;

        if ($isSelected) {
            $this->attributes[static::ATTRIBUTE_SELECTED] = null;
        }

        parent::__construct($content, $attributes, $translator, $tag);
    }
}
