<?php

declare(strict_types=1);

namespace AbterPhp\Files\Grid\Filters;

use AbterPhp\Framework\Grid\Collection\Filters;
use AbterPhp\Framework\Grid\Filter\Input;
use AbterPhp\Framework\I18n\ITranslator;

class File extends Filters
{
    /**
     * File constructor.
     *
     * @param string|null      $tag
     * @param array            $attributes
     * @param ITranslator|null $translator
     */
    public function __construct(?string $tag = null, array $attributes = [], ITranslator $translator = null)
    {
        $this->components[] = new Input(
            'username',
            'username',
            'files:userUsername',
            Input::FILTER_LIKE,
            [],
            $translator
        );

        parent::__construct($tag, $attributes, $translator);
    }
}
