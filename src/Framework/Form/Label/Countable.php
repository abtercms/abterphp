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
     * @param string|null      $tag
     * @param array            $attributes
     * @param ITranslator|null $translator
     */
    public function __construct(
        string $inputId,
        $content,
        int $size = 160,
        ?string $tag = null,
        array $attributes = [],
        ?ITranslator $translator = null
    ) {
        parent::__construct($inputId, $content, $tag, $attributes, $translator);

        $this->content .= sprintf('<span data-count="%d" class="count"></span>', $size);
    }
}
