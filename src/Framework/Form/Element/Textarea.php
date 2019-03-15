<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Element;

use AbterPhp\Framework\Html\Component\Component;
use AbterPhp\Framework\Html\Helper\StringHelper;
use AbterPhp\Framework\I18n\ITranslator;

class Textarea extends Component implements IElement
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
     * @param string|null      $tag
     * @param array            $attributes
     * @param ITranslator|null $translator
     */
    public function __construct(
        string $inputId,
        string $name,
        string $value = '',
        ?string $tag = null,
        array $attributes = [],
        ?ITranslator $translator = null
    ) {
        if ($inputId) {
            $attributes[static::ATTRIBUTE_ID] = $inputId;
        }
        if (!array_key_exists(static::ATTRIBUTE_ROWS, $attributes)) {
            $attributes[static::ATTRIBUTE_ROWS] = static::DEFAULT_ROW;
        }

        $attributes[static::ATTRIBUTE_NAME]  = $name;
        $attributes[static::ATTRIBUTE_VALUE] = $value;

        parent::__construct('', $tag, $attributes, $translator);
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
