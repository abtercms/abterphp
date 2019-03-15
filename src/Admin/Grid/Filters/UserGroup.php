<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Grid\Filters;

use AbterPhp\Framework\Grid\Collection\Filters;
use AbterPhp\Framework\Grid\Filter\Input;
use AbterPhp\Framework\I18n\ITranslator;

class UserGroup extends Filters
{
    /**
     * UserGroup constructor.
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
            'admin:userGroupIdentifier',
            Input::FILTER_EXACT,
            [],
            $translator
        );
        $this->components[] = new Input(
            'name',
            'name',
            'admin:userGroupName',
            Input::FILTER_LIKE,
            [],
            $translator
        );

        parent::__construct($tag, $attributes, $translator);
    }
}
