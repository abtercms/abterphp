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
     * @param array            $attributes
     * @param ITranslator|null $translator
     * @param string|null      $tag
     */
    public function __construct(array $attributes = [], ITranslator $translator = null, ?string $tag = null)
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

        parent::__construct($attributes, $translator, $tag);
    }
}
