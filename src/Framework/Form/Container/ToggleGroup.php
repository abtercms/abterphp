<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Container;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\Html\Component;
use AbterPhp\Framework\Html\Helper\StringHelper;
use AbterPhp\Framework\Html\INode;
use AbterPhp\Framework\Html\INodeContainer;

class ToggleGroup extends FormGroup
{
    const SPAN_INTENT = 'toggle-span';

    /** @var Component */
    protected $toggleSpan;

    /**
     * ToggleGroup constructor.
     *
     * @param Input       $input
     * @param Label       $label
     * @param INode|null  $help
     * @param string[]    $intents
     * @param array       $attributes
     * @param string|null $tag
     */
    public function __construct(
        Input $input,
        Label $label,
        ?INode $help = null,
        array $intents = [],
        array $attributes = [],
        ?string $tag = null
    ) {
        $input->setAttribute(Html5::ATTR_TYPE, Input::TYPE_CHECKBOX);

        parent::__construct($input, $label, $help, $intents, $attributes, $tag);

        $this->toggleSpan = new Component(null, [static::SPAN_INTENT], [], Html5::TAG_SPAN);
    }

    /**
     * @return INode[]
     */
    public function getNodes(): array
    {
        return $this->getAllNodes(0);
    }

    /**
     * @param int $depth
     *
     * @return array
     */
    public function getAllNodes(int $depth = -1): array
    {
        $nodes = [$this->label, $this->input, $this->toggleSpan];
        if ($this->help) {
            $nodes[] = $this->help;
        }

        if ($depth !== 0) {
            $nodes = array_merge(
                $nodes,
                $this->label->getAllNodes($depth - 1),
                $this->input->getAllNodes($depth - 1),
                $this->toggleSpan->getAllNodes($depth - 1)
            );

            if ($this->help instanceof INodeContainer) {
                $nodes = array_merge($nodes, $this->help->getAllNodes($depth - 1));
            }
        }

        return array_merge($nodes, parent::getAllNodes($depth));
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $this->label->setContent([$this->input, $this->toggleSpan]);

        $help = $this->help ?: '';

        $content = (string)$this->label . (string)$help;

        $content = StringHelper::wrapInTag($content, $this->tag, $this->attributes);

        return $content;
    }
}
