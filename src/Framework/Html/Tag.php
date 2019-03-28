<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html;

use AbterPhp\Framework\Html\Helper\StringHelper;

class Tag extends Node implements ITag
{
    use TagTrait;

    /**
     * Row constructor.
     *
     * @param string|null $content
     * @param string[]    $intents
     * @param array       $attributes
     * @param string|null $tag
     */
    public function __construct(
        ?string $content = null,
        array $intents = [],
        array $attributes = [],
        ?string $tag = null
    ) {
        parent::__construct($content, $intents);

        $this->setAttributes($attributes);
        $this->setTag($tag);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $content = StringHelper::wrapInTag($this->content, $this->tag, $this->attributes);

        return $content;
    }
}
