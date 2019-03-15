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
     * @param string|null      $tag
     * @param array            $attributes
     * @param ITranslator|null $translator
     */
    public function __construct(?string $tag = null, array $attributes = [], ITranslator $translator = null)
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

        parent::__construct($tag, $attributes, $translator);
    }
}
