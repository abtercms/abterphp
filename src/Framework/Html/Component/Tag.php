<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html\Component;

use AbterPhp\Framework\Helper\ArrayHelper;
use AbterPhp\Framework\Html\Helper\StringHelper;
use AbterPhp\Framework\I18n\ITranslator;

/**
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
class Tag implements IComponent
{
    const DEFAULT_TAG = self::TAG_DIV;

    const TAG_DIV = 'div';
    const TAG_P   = 'p';

    const ATTRIBUTE_CLASS = 'class';
    const ATTRIBUTE_ROLE  = 'role';
    const ATTRIBUTE_ID    = 'id';
    const ATTRIBUTE_HREF  = 'href';

    const ROLE_NAVIGATION = 'navigation';

    const ERROR_MSG_INVALID_CONTENT = 'content must be a string or an instance of %s';

    /** @var string|IComponent */
    protected $content;

    /** @var string[] */
    protected $attributes = [];

    /** @var ITranslator */
    protected $translator;

    /** @var string|null */
    protected $tag;

    /**
     * Tag constructor.
     *
     * @param string|IComponent $content
     * @param string[]          $attributes
     * @param ITranslator|null  $translator
     * @param string|null       $tag
     */
    public function __construct(
        $content = '',
        array $attributes = [],
        ?ITranslator $translator = null,
        ?string $tag = null
    ) {
        if ($content) {
            $this->setContent($content);
        }
        $this->mergeAttributes($attributes);
        $this->setTag($tag);
        $this->setTranslator($translator);
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
     * @return string|IComponent
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string|IComponent
     *
     * @return $this
     */
    public function setContent($content): IComponent
    {
        if (!is_string($content) && !($content instanceof IComponent)) {
            throw new \InvalidArgumentException(sprintf(static::ERROR_MSG_INVALID_CONTENT, IComponent::class));
        };

        $this->content = $content;

        return $this;
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
    public function mergeAttributes(array $attributes = []): IComponent
    {
        $this->attributes = ArrayHelper::mergeAttributes($this->attributes, $attributes);

        return $this;
    }

    /**
     * @param array $attributes
     *
     * @return $this
     */
    public function setAttributes(array $attributes = []): IComponent
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @param string $attribute
     * @param string $valueToAppend
     */
    public function appendToAttribute(string $attribute, string $valueToAppend)
    {
        $this->mergeAttributes([$attribute => $valueToAppend]);
    }

    /**
     * @param ITranslator|null $translator
     *
     * @return $this
     */
    public function setTranslator(?ITranslator $translator = null): IComponent
    {
        $this->translator = $translator;

        return $this;
    }

    /**
     * @param string|null $tag
     *
     * @return $this
     */
    public function setTag(?string $tag = null): IComponent
    {
        $this->tag = $tag ?: static::DEFAULT_TAG;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $content = $this->content;

        if ($this->translator) {
            $content = $this->translator->translate((string)$this->content);

            if (substr($content, 0, 2) === '{{') {
                $content = $this->content;
            }
        }

        return StringHelper::wrapInTag($content, $this->tag, $this->attributes);
    }
}
