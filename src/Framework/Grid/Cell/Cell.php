<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Cell;

use AbterPhp\Framework\Html\Component\Component;
use AbterPhp\Framework\I18n\ITranslator;

class Cell extends Component implements ICell
{
    const HEAD = 'th';
    const BODY = 'td';

    /** @var string */
    protected $group = '';

    /**
     * @param string $content
     * @param string $group
     * @param array $attributes
     * @param string $tag
     * @param ITranslator|null $translator
     */
    public function __construct(
        string $content,
        string $group,
        array $attributes = [],
        string $tag = self::BODY,
        ITranslator $translator = null
    ) {
        $this->group = $group;

        parent::__construct($content, $tag, $attributes, $translator);

        $this->appendToAttribute(Component::ATTRIBUTE_CLASS, $tag . '-' . $group);
    }

    /**
     * @return string
     */
    public function getGroup(): string
    {
        return $this->group;
    }
}
