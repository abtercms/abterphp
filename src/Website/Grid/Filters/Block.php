<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Filters;

use AbterPhp\Framework\Grid\Collection\Filters;
use AbterPhp\Framework\Grid\Filter\Input;
use AbterPhp\Framework\I18n\ITranslator;

class Block extends Filters
{
    /**
     * Block constructor.
     *
     * @param ITranslator $translator
     */
    public function __construct(ITranslator $translator)
    {
        $this->components[] = new Input(
            'identifier',
            'identifier',
            'pages:blockIdentifier',
            Input::FILTER_EXACT,
            [],
            $translator
        );
        $this->components[] = new Input(
            'title',
            'title',
            'pages:blockTitle',
            Input::FILTER_LIKE,
            [],
            $translator
        );

        parent::__construct([], $translator);
    }
}
