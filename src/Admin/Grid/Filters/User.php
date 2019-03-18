<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Grid\Filters;

use AbterPhp\Framework\Grid\Collection\Filters;
use AbterPhp\Framework\Grid\Filter\Input;
use AbterPhp\Framework\I18n\ITranslator;

class User extends Filters
{
    /**
     * User constructor.
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
            'admin:userUsername',
            Input::FILTER_LIKE,
            [],
            $translator
        );

        $this->components[] = new Input(
            'email',
            'email',
            'admin:userEmail',
            Input::FILTER_EXACT,
            [],
            $translator
        );

        parent::__construct($attributes, $translator, $tag);
    }
}
