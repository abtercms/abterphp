<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html\Component;

use AbterPhp\Framework\Html\Helper\StringHelper;
use AbterPhp\Framework\I18n\ITranslator;

/**
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
class Component implements IComponent
{
    const DEFAULT_TAG = self::TAG_DIV;

    const TAG_DIV = 'div';
    const TAG_P   = 'p';

    const ATTRIBUTE_CLASS = 'class';
    const ATTRIBUTE_ID    = 'id';
    const ATTRIBUTE_HREF  = 'href';

    const ERROR_MSG_INVALID_CONTENT = 'content must be a string or IComponent instance';

    /** @var string|IComponent */
    protected $content;

    /** @var string|null */
    protected $tag;

    /** @var string[] */
    protected $attributes = [];

    /** @var ITranslator */
    protected $translator;

    /**
     * Component constructor.
     *
     * @param string|IComponent $content
     * @param string|null       $tag
     * @param string[]          $attributes
     * @param ITranslator|null  $translator
     */
    public function __construct(
        $content = '',
        ?string $tag = null,
        array $attributes = [],
        ITranslator $translator = null
    ) {
        if (!is_string($content) && !($content instanceof IComponent)) {
            throw new \InvalidArgumentException(static::ERROR_MSG_INVALID_CONTENT);
        }

        $this->content    = $content;
        $this->tag        = $tag ?: static::DEFAULT_TAG;
        $this->translator = $translator;

        foreach ($attributes as $key => $value) {
            if (array_key_exists($key, $this->attributes)) {
                $this->attributes[$key] = array_merge((array)$this->attributes[$key], (array)$value);
            } else {
                $this->attributes[$key] = $value;
            }
        }
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        if (array_key_exists(static::ATTRIBUTE_ID, $this->attributes)) {
            return (string)$this->attributes[static::ATTRIBUTE_ID];
        }

        return '';
    }

    /**
     * @param string $tag
     *
     * @return $this
     */
    public function setTag(string $tag): IComponent
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return (string)$this->content;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     *
     * @return $this
     */
    public function setAttributes(array $attributes = []): IComponent
    {
        return $this->attributes;
    }

    /**
     * @param string $attribute
     * @param string $valueToAppend
     */
    public function appendToAttribute(string $attribute, string $valueToAppend)
    {
        $currentValue = isset($this->attributes[$attribute]) ? $this->attributes[$attribute] : '';

        $pieces = explode(' ', $currentValue);

        $pieces[] = $valueToAppend;

        $pieces = array_unique($pieces);
        $pieces = array_filter($pieces);

        $this->attributes[$attribute] = implode(' ', $pieces);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $content = $this->content;

        if ($this->translator) {
            $content = $this->translator->translate($this->content);

            if (substr($content, 0, 2) === '{{') {
                $content = $this->content;
            }
        }

        return StringHelper::wrapInTag($content, $this->tag, $this->attributes);
    }
}
