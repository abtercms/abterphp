<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Element;

use AbterPhp\Framework\Html\Component\Tag;
use AbterPhp\Framework\Html\Helper\StringHelper;
use AbterPhp\Framework\I18n\ITranslator;

class Textarea extends Tag implements IElement
{
    const DEFAULT_TAG = self::TAG_TEXTAREA;

    const DEFAULT_ROW = '3';

    const TAG_TEXTAREA = 'textarea';

    const ATTRIBUTE_NAME  = 'name';
    const ATTRIBUTE_VALUE = 'value';
    const ATTRIBUTE_ROWS  = 'rows';

    const CLASS_WYSIWYG   = 'wysiwyg';

    /** @var array */
    protected $attributes = [
        self::ATTRIBUTE_CLASS => 'form-control',
    ];

    /**
     * Textarea constructor.
     *
     * @param string           $inputId
     * @param string           $name
     * @param string           $value
     * @param array            $attributes
     * @param ITranslator|null $translator
     * @param string|null      $tag
     */
    public function __construct(
        string $inputId,
        string $name,
        string $value = '',
        array $attributes = [],
        ?ITranslator $translator = null,
        ?string $tag = null
    ) {
        if ($inputId) {
            $attributes[static::ATTRIBUTE_ID] = $inputId;
        }
        if (!array_key_exists(static::ATTRIBUTE_ROWS, $attributes)) {
            $attributes[static::ATTRIBUTE_ROWS] = static::DEFAULT_ROW;
        }

        $attributes[static::ATTRIBUTE_NAME]  = $name;
        $attributes[static::ATTRIBUTE_VALUE] = $value;

        parent::__construct('', $attributes, $translator, $tag);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        if (array_key_exists(static::ATTRIBUTE_NAME, $this->attributes)) {
            return (string)$this->attributes[static::ATTRIBUTE_NAME];
        }

        return '';
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setValue(string $value): IElement
    {
        $this->attributes[static::ATTRIBUTE_VALUE] = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $attributes = $this->attributes;
        $content    = $attributes[static::ATTRIBUTE_VALUE];
        unset($attributes[static::ATTRIBUTE_VALUE]);

        return StringHelper::wrapInTag($content, $this->tag, $attributes);
    }
}
