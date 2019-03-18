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
     * @param array            $attributes
     * @param ITranslator|null $translator
     * @param string|null      $tag
     */
    public function __construct(array $attributes = [], ITranslator $translator = null, ?string $tag = null)
    {
        $this->components[] = new Input(
            'username',
            'username',
            'files:userUsername',
            Input::FILTER_LIKE,
            [],
            $translator
        );

        parent::__construct($attributes, $translator, $tag);
    }
}
