<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html\Component;

use AbterPhp\Framework\I18n\ITranslator;

/**
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
class Component implements IComponent
{
    /** @var string|IComponent */
    protected $content;

    /** @var ITranslator */
    protected $translator;

    /**
     * Component constructor.
     *
     * @param string           $content
     * @param ITranslator|null $translator
     */
    public function __construct(string $content = '', ?ITranslator $translator = null)
    {
        $this->setContent($content);
        $this->setTranslator($translator);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return '';
    }

    /**
     * @param string $tag
     *
     * @return $this
     */
    public function setTag(string $tag): IComponent
    {
        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return [];
    }

    /**
     * @param array $attributes
     *
     * @return $this
     */
    public function setAttributes(array $attributes = []): IComponent
    {
        return $this;
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
        $this->content = $content;

        return $this;
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

        return (string)$content;
    }
}
