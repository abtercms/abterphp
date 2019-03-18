<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Label;

use AbterPhp\Framework\Html\Component\Tag;
use AbterPhp\Framework\I18n\ITranslator;

class Label extends Tag
{
    const DEFAULT_TAG = 'label';

    const ATTRIBUTE_FOR = 'for';

    /** @var string */
    protected $template;

    /** @var array */
    protected $attributes = [
        self::ATTRIBUTE_CLASS => 'control-label',
        self::ATTRIBUTE_FOR   => '',
    ];

    /**
     * Label constructor.
     *
     * @param string           $inputId
     * @param string           $content
     * @param array            $attributes
     * @param ITranslator|null $translator
     * @param string|null      $tag
     */
    public function __construct(
        string $inputId,
        $content,
        array $attributes = [],
        ?ITranslator $translator = null,
        ?string $tag = null
    ) {
        $this->attributes[static::ATTRIBUTE_FOR] = $inputId;

        if ($translator && $translator->canTranslate($content)) {
            $content = $translator->translate($content);
        }

        parent::__construct($content, $attributes, $translator, $tag);
    }
}
