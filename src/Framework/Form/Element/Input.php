<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Element;

use AbterPhp\Framework\Html\Component\Tag;
use AbterPhp\Framework\Html\Helper\StringHelper;
use AbterPhp\Framework\I18n\ITranslator;

class Input extends Tag implements IElement
{
    const DEFAULT_TAG = self::TAG_INPUT;

    const TAG_INPUT = 'input';

    const DEFAULT_TYPE = self::TYPE_TEXT;

    const ATTRIBUTE_NAME         = 'name';
    const ATTRIBUTE_TYPE         = 'type';
    const ATTRIBUTE_VALUE        = 'value';
    const ATTRIBUTE_AUTOCOMPLETE = 'autocomplete';
    const ATTRIBUTE_CHECKED      = 'checked';

    const TYPE_BUTTON         = 'button';
    const TYPE_CHECKBOX       = 'checkbox';
    const TYPE_COLOR          = 'color';
    const TYPE_DATE           = 'date';
    const TYPE_DATETIME       = 'datetime';
    const TYPE_DATETIME_LOCAL = 'datetime-local';
    const TYPE_EMAIL          = 'email';
    const TYPE_FILE           = 'file';
    const TYPE_HIDDEN         = 'hidden';
    const TYPE_IMAGE          = 'image';
    const TYPE_MONTH          = 'month';
    const TYPE_NUMBER         = 'number';
    const TYPE_PASSWORD       = 'password';
    const TYPE_RADIO          = 'radio';
    const TYPE_RANGE          = 'range';
    const TYPE_RESET          = 'reset';
    const TYPE_SEARCH         = 'search';
    const TYPE_SUBMIT         = 'submit';
    const TYPE_TEL            = 'tel';
    const TYPE_TEXT           = 'text';
    const TYPE_URL            = 'url';
    const TYPE_WEEK           = 'week';

    const NAME_HTTP_METHOD = '_method';

    const AUTOCOMPLETE_OFF = 'off';

    /** @var array */
    protected $attributes = [
        self::ATTRIBUTE_CLASS => 'form-control',
    ];

    /**
     * Input constructor.
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
        if (!array_key_exists(static::ATTRIBUTE_TYPE, $attributes)) {
            $attributes[static::ATTRIBUTE_TYPE] = static::DEFAULT_TYPE;
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
        return StringHelper::createTag($this->tag, $this->attributes);
    }
}
