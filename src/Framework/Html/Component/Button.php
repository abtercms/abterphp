<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html\Component;

use AbterPhp\Framework\I18n\ITranslator;

class Button extends Component
{
    const DEFAULT_TAG = 'button';

    const TAG_A      = 'a';
    const TAG_BUTTON = 'button';

    const ATTRIBUTE_HREF     = 'href';
    const ATTRIBUTE_DISABLED = 'disabled';
    const ATTRIBUTE_NAME     = 'name';
    const ATTRIBUTE_TYPE     = 'type';
    const ATTRIBUTE_VALUE    = 'value';

    const CLASS_PRIMARY   = 'btn btn-primary';
    const CLASS_SECONDARY = 'btn btn-secondary';
    const CLASS_DANGER    = 'btn btn-danger';
    const CLASS_SUCCESS   = 'btn btn-success';
    const CLASS_INFO      = 'btn btn-info';
    const CLASS_WARNING   = 'btn btn-warning';
    const CLASS_LINK      = 'btn btn-link';

    const CLASS_SMALL = 'btn-sm';
    const CLASS_LARGE = 'btn-lg';

    const CLASS_HIDDEN = 'hidden';

    const TYPE_SUBMIT = 'submit';

    /**
     * @param IComponent|string $content
     * @param string            $tag
     * @param array             $attributes
     * @param array             $attributeCallbacks
     * @param ITranslator|null  $translator
     */
    public function __construct(
        $content,
        string $tag = self::TAG_A,
        array $attributes = [],
        ITranslator $translator = null
    ) {
        parent::__construct($content, $tag, $attributes, $translator);
    }
}
