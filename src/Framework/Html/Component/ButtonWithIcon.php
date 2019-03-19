<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html\Component;

use AbterPhp\Framework\I18n\ITranslator;

class ButtonWithIcon extends Button
{
    const DEFAULT_TEMPLATE = '%2$s %1$s';

    /** @var string */
    protected $template = self::DEFAULT_TEMPLATE;

    /** @var IComponent */
    protected $text;

    /** @var IComponent */
    protected $icon;

    /**
     * Button constructor.
     *
     * @param IComponent|string $content
     * @param array            $attributes
     * @param ITranslator|null $translator
     * @param string|null      $tag
     */
    public function __construct(
        IComponent $text,
        IComponent $icon,
        array $attributes = [],
        ITranslator $translator = null,
        ?string $tag = null
    ) {
        parent::__construct('', $attributes, $translator, $tag);

        $this->text = $text;
        $this->icon = $icon;
    }

    /**
     * @param string $template
     *
     * @return $this
     */
    public function setTemplate(string $template): ButtonWithIcon
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $this->content = sprintf(
            $this->template,
            (string)$this->text,
            (string)$this->icon
        );

        return parent::__toString();
    }
}
