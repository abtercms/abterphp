<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Container;

use AbterPhp\Framework\Form\Element\IElement;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\Html\Component\Tag;
use AbterPhp\Framework\Html\Component\IComponent;
use AbterPhp\Framework\I18n\ITranslator;

class FormGroup extends Tag implements IContainer
{
    const DEFAULT_TEMPLATE = '%1$s%2$s%3$s';

    const CLASS_COUNTABLE = 'countable';

    /** @var string[] */
    protected $attributes = [
        self::ATTRIBUTE_CLASS => 'form-group',
    ];

    /** @var IElement|null */
    protected $input;

    /** @var Label|null */
    protected $label;

    /** @var IComponent|null */
    protected $help;

    /** @var string */
    protected $template = self::DEFAULT_TEMPLATE;

    /**
     * FormGroup constructor.
     *
     * @param IElement|null    $input
     * @param Label|null       $label
     * @param IComponent|null  $help
     * @param array            $attributes
     * @param ITranslator|null $translator
     * @param string|null      $tag
     */
    public function __construct(
        ?IElement $input,
        ?Label $label = null,
        ?IComponent $help = null,
        array $attributes = [],
        ?ITranslator $translator = null,
        ?string $tag = null
    ) {
        parent::__construct('', $attributes, $translator, $tag);

        $this->label = $label;
        $this->input = $input;
        $this->help  = $help;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setValue(string $value): IElement
    {
        $this->input->setValue($value);

        return $this;
    }

    /**
     * @return IElement|null
     */
    public function getInput(): ?IElement
    {
        return $this->input;
    }

    /**
     * @return Label|null
     */
    public function getLabel(): ?Label
    {
        return $this->label;
    }

    /**
     * @return IComponent|null
     */
    public function getHelp(): ?IComponent
    {
        return $this->help;
    }

    /**
     * @return IElement[]
     */
    public function getElements(): array
    {
        return [$this->input];
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
        if (empty($this->content)) {
            $this->content = sprintf($this->template, (string)$this->label, (string)$this->input, (string)$this->help);
        }

        return parent::__toString();
    }
}
