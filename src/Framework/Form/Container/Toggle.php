<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Container;

use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Label\ToggleLabel;
use AbterPhp\Framework\Html\Component\IComponent;
use AbterPhp\Framework\I18n\ITranslator;

class Toggle extends FormGroup
{
    const DEFAULT_TEMPLATE = '<label class="pmd-checkbox pmd-checkbox-ripple-effect">%1$s%2$s</label>%3$s';

    /** @var string[] */
    protected $attributes = [
        self::ATTRIBUTE_CLASS => 'checkbox pmd-default-theme',
    ];

    /**
     * Toggle constructor.
     *
     * @param Input            $input
     * @param ToggleLabel      $label
     * @param IComponent|null  $help
     * @param array            $attributes
     * @param ITranslator|null $translator
     * @param string|null      $tag
     */
    public function __construct(
        Input $input,
        ToggleLabel $label,
        ?IComponent $help = null,
        array $attributes = [],
        ?ITranslator $translator = null,
        ?string $tag = null
    ) {
        parent::__construct($input, $label, $help, $attributes, $translator, $tag);

        $this->template = static::DEFAULT_TEMPLATE;
    }
}
