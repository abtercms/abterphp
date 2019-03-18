<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Cell;

use AbterPhp\Framework\Html\Component\Tag;
use AbterPhp\Framework\I18n\ITranslator;

class Cell extends Tag implements ICell
{
    const DEFAULT_TAG = self::BODY;

    const HEAD = 'th';
    const BODY = 'td';

    /** @var string */
    protected $group = '';

    /**
     * Cell constructor.
     *
     * @param string           $content
     * @param string           $group
     * @param array            $attributes
     * @param ITranslator|null $translator
     * @param string|null      $tag
     */
    public function __construct(
        string $content,
        string $group,
        array $attributes = [],
        ?ITranslator $translator = null,
        ?string $tag = null
    ) {
        $this->group = $group;

        parent::__construct($content, $attributes, $translator, $tag);

        $this->appendToAttribute(Tag::ATTRIBUTE_CLASS, $this->tag . '-' . $group);
    }

    /**
     * @return string
     */
    public function getGroup(): string
    {
        return $this->group;
    }
}
