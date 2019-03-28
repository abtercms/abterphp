<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html;

use AbterPhp\Framework\Html\Helper\StringHelper;

final class Contentless extends Component
{
    /**
     * Contentless constructor.
     *
     * @param array       $intents
     * @param array       $attributes
     * @param string|null $tag
     */
    public function __construct($intents = [], array $attributes = [], ?string $tag = null)
    {
        parent::__construct(null, $intents, $attributes, $tag);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        if ($this->nodes) {
            throw new \LogicException('Contentless must not have nodes');
        }

        $content = StringHelper::createTag($this->tag, $this->attributes);

        return $content;
    }
}
