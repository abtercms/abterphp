<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Label;

use AbterPhp\Framework\I18n\ITranslator;

class Countable extends Label
{
    const DEFAULT_SIZE = 160;

    /**
     * Label constructor.
     *
     * @param string           $inputId
     * @param string           $content
     * @param int              $size
     * @param array            $attributes
     * @param ITranslator|null $translator
     * @param string|null      $tag
     */
    public function __construct(
        string $inputId,
        $content,
        int $size = 160,
        array $attributes = [],
        ?ITranslator $translator = null,
        ?string $tag = null
    ) {
        parent::__construct($inputId, $content, $attributes, $translator, $tag);

        $this->content .= sprintf('<span data-count="%d" class="count"></span>', $size);
    }
}
