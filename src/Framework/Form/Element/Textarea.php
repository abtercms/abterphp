<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Element;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Html\Helper\Tag as TagHelper;
use AbterPhp\Framework\Html\Tag;

class Textarea extends Input implements IElement
{
    public const CLASS_WYSIWYG = 'wysiwyg';

    public const DEFAULT_ROW = '3';

    public const DEFAULT_EDITOR_ROW = '15';

    protected const DEFAULT_TAG = Html5::TAG_TEXTAREA;

    public const EDITOR_ATTRIBS = [
        Html5::ATTR_CLASS => self::CLASS_WYSIWYG,
        Html5::ATTR_ROWS  => self::DEFAULT_EDITOR_ROW,
    ];

    protected const PROTECTED_KEYS = [Html5::ATTR_ID, Html5::ATTR_NAME, Html5::ATTR_ROWS, Html5::ATTR_VALUE];

    /**
     * Textarea constructor.
     *
     * @param string              $inputId
     * @param string              $name
     * @param string              $value
     * @param string[]            $intents
     * @param array<string,mixed> $attributes
     * @param string|null         $tag
     */
    public function __construct(
        string $inputId,
        string $name,
        string $value = '',
        array $intents = [],
        array $attributes = [],
        ?string $tag = null
    ) {
        Tag::__construct(null, $intents, $attributes, $tag);

        $this->addAttribute(Html5::ATTR_ID, $inputId);
        if (!in_array(Html5::ATTR_ROWS, $this->attributes)) {
            $this->addAttribute(Html5::ATTR_ROWS, static::DEFAULT_ROW);
        }

        $this->addAttribute(Html5::ATTR_NAME, $name);

        $this->setValue($value);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $value = $this->attributes[Html5::ATTR_VALUE]->getValue();

        $attributes = $this->attributes;
        unset($attributes[Html5::ATTR_VALUE]);

        return TagHelper::toString($this->tag, $value, $attributes);
    }
}
