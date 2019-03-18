<?php

declare(strict_types=1);

namespace AbterPhp\Files\Grid\Filters;

use AbterPhp\Framework\Grid\Collection\Filters;
use AbterPhp\Framework\Grid\Filter\Input;
use AbterPhp\Framework\I18n\ITranslator;

class FileCategory extends Filters
{
    /**
     * FileCategory constructor.
     *
     * @param string|null      $tag
     * @param array            $attributes
     * @param ITranslator|null $translator
     */
    public function __construct(array $attributes = [], ITranslator $translator = null, ?string $tag = null)
    {
        $this->components[] = new Input(
            'identifier',
            'identifier',
            'files:fileCategoryIdentifier',
            Input::FILTER_EXACT,
            [],
            $translator
        );

        $this->components[] = new Input(
            'name',
            'name',
            'files:fileCategoryName',
            Input::FILTER_LIKE,
            [],
            $translator
        );

        parent::__construct($attributes, $translator, $tag);
    }
}
