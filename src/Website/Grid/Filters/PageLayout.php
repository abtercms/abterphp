<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Filters;

use AbterPhp\Framework\Grid\Collection\Filters;
use AbterPhp\Framework\Grid\Filter\Input;
use AbterPhp\Framework\I18n\ITranslator;

class PageLayout extends Filters
{
    /**
     * PageLayout constructor.
     *
     * @param array            $attributes
     * @param ITranslator|null $translator
     * @param string|null      $tag
     */
    public function __construct(array $attributes = [], ?ITranslator $translator = null, ?string $tag = null)
    {
        $this->components[] = new Input(
            'identifier',
            'identifier',
            'pages:pageLayoutIdentifier',
            Input::FILTER_EXACT,
            [],
            $translator
        );

        parent::__construct($attributes, $translator, $tag);
    }
}
