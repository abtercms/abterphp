<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Container;

use AbterPhp\Framework\Form\Element\IElement;
use AbterPhp\Framework\Html\Collection\Collection;
use AbterPhp\Framework\I18n\ITranslator;

class Hideable extends Collection implements IContainer
{
    const DEFAULT_TEMPLATE = '
        <div class="hidable">
            <hr>
            <p class="hider">
                <button class="btn btn-info"
                        type="button">%1$s</button>
            </p>
            <div class="meta-container hidee">
                %2$s
            </div>
        </div>';

    /** @var string */
    protected $template = self::DEFAULT_TEMPLATE;

    /** @var string */
    protected $moreBtnLabel;

    /**
     * Hideable constructor.
     *
     * @param string           $moreBtnLabel
     * @param array            $attributes
     * @param ITranslator|null $translator
     * @param string|null      $tag
     */
    public function __construct(
        string $moreBtnLabel,
        array $attributes = [],
        ?ITranslator $translator = null,
        ?string $tag = null
    ) {
        parent::__construct($attributes, $translator, $tag);

        $this->moreBtnLabel = $moreBtnLabel;
    }

    /**
     * @return IElement[]
     */
    public function getElements(): array
    {
        $elements = [];
        foreach ($this->components as $component) {
            if ($component instanceof IContainer) {
                $elements = array_merge($elements, $component->getElements());
            }
            if ($component instanceof IElement) {
                $elements[] = $component;
            }
        }

        return $elements;
    }

    /**
     * @param string $template
     *
     * @return $this
     */
    public function setTemplate(string $template): IContainer
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $components = parent::__toString();

        $moreBtnLabel = $this->moreBtnLabel;
        if ($this->translator && $this->translator->canTranslate($moreBtnLabel)) {
            $moreBtnLabel = $this->translator->translate($moreBtnLabel);
        }

        return sprintf(
            $this->template,
            $moreBtnLabel,
            $components
        );
    }
}
