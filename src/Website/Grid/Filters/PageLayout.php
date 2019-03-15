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
     * @param string|null      $tag
     * @param array            $attributes
     * @param ITranslator|null $translator
     */
    public function __construct(?string $tag = null, array $attributes = [], ITranslator $translator = null)
    {
        $this->components[] = new Input(
            'identifier',
            'identifier',
            'pages:pageLayoutIdentifier',
            Input::FILTER_EXACT,
            [],
            $translator
        );

        parent::__construct($tag, $attributes, $translator);
    }
}
