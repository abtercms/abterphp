<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Element;

use AbterPhp\Framework\Html\Collection\Options;
use AbterPhp\Framework\I18n\ITranslator;

class Select extends Options implements IElement
{
    const DEFAULT_TAG = 'select';

    const ATTRIBUTE_NAME     = 'name';
    const ATTRIBUTE_VALUE    = 'value';
    const ATTRIBUTE_MULTIPLE = 'multiple';
    const ATTRIBUTE_SIZE     = 'size';

    /** @var Options */
    protected $components;

    /** @var array */
    protected $attributes = [
        self::ATTRIBUTE_CLASS => 'form-control',
    ];

    /** @var string */
    protected $value = '';

    /**
     * Select constructor.
     *
     * @param string           $inputId
     * @param string           $name
     * @param string           $value
     * @param bool             $multiple
     * @param string|null      $tag
     * @param array            $attributes
     * @param ITranslator|null $translator
     */
    public function __construct(
        string $inputId,
        string $name,
        string $value = '',
        bool $multiple = false,
        ?string $tag = null,
        array $attributes = [],
        ?ITranslator $translator = null
    ) {
        if ($inputId) {
            $attributes[static::ATTRIBUTE_ID] = $inputId;
        }
        $attributes[static::ATTRIBUTE_NAME] = $name;
        if (array_key_exists(static::ATTRIBUTE_VALUE, $attributes)) {
            $this->value = $attributes[static::ATTRIBUTE_VALUE];
            unset($attributes[static::ATTRIBUTE_VALUE]);
        }

        if ($multiple) {
            $attributes[static::ATTRIBUTE_MULTIPLE] = null;
        }

        parent::__construct($tag, $attributes, $translator);
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setValue(string $value): IElement
    {
        $this->value = $value;

        return $this;
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
}
