<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Filters;

use AbterPhp\Framework\Grid\Collection\Filters;
use AbterPhp\Framework\Grid\Filter\Input;
use AbterPhp\Framework\I18n\ITranslator;

class Page extends Filters
{
    /**
     * Page constructor.
     *
     * @param array            $attributes
     * @param ITranslator|null $translator
     * @param string|null      $tag
     */
    public function __construct(array $attributes = [], ITranslator $translator = null, ?string $tag = null)
    {
        $this->components[] = new Input(
            'identifier',
            'identifier',
            'pages:pageIdentifier',
            Input::FILTER_EXACT,
            [],
            $translator
        );
        $this->components[] = new Input(
            'title',
            'title',
            'pages:pageTitle',
            Input::FILTER_LIKE,
            [],
            $translator
        );

        parent::__construct($attributes, $translator, $tag);
    }
}
