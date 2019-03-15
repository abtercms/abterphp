<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Label;

use AbterPhp\Framework\Html\Component\Component;
use AbterPhp\Framework\I18n\ITranslator;

class Label extends Component
{
    const DEFAULT_TAG = 'label';

    const ATTRIBUTE_FOR = 'for';

    /** @var string */
    protected $template;

    /** @var array */
    protected $attributes = [
        self::ATTRIBUTE_CLASS => 'control-label',
    ];

    /**
     * Label constructor.
     *
     * @param string           $inputId
     * @param string           $content
     * @param string|null      $tag
     * @param array            $attributes
     * @param ITranslator|null $translator
     */
    public function __construct(
        string $inputId,
        $content,
        ?string $tag = null,
        array $attributes = [],
        ?ITranslator $translator = null
    ) {
        $attributes[static::ATTRIBUTE_FOR] = $inputId;

        if ($translator && $translator->canTranslate($content)) {
            $content = $translator->translate($content);
        }

        parent::__construct($content, $tag, $attributes, $translator);
    }
}
