<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form;

use AbterPhp\Framework\Html\Collection\Collection;
use AbterPhp\Framework\I18n\ITranslator;
use Opulence\Http\Requests\RequestMethods;

class Form extends Collection implements IForm
{
    const DEFAULT_TAG = self::TAG_FORM;

    const TAG_FORM = 'form';

    const ATTRIBUTE_ACTION  = 'action';
    const ATTRIBUTE_METHOD  = 'method';
    const ATTRIBUTE_ENCTYPE = 'enctype';

    const ENCTYPE_MULTIPART = 'multipart/form-data';

    /**
     * Form constructor.
     *
     * @param string           $action
     * @param string           $method
     * @param array            $attributes
     * @param ITranslator|null $translator
     * @param string|null      $tag
     */
    public function __construct(
        string $action,
        string $method = RequestMethods::POST,
        array $attributes = [],
        ?ITranslator $translator = null,
        ?string $tag = null
    ) {
        $attributes[static::ATTRIBUTE_ACTION] = $action;
        $attributes[static::ATTRIBUTE_METHOD] = $method;

        parent::__construct($attributes, $translator, $tag);
    }
}
