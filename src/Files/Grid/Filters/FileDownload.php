<?php

declare(strict_types=1);

namespace AbterPhp\Files\Grid\Filters;

use AbterPhp\Framework\Grid\Collection\Filters;
use AbterPhp\Framework\Grid\Filter\Input;
use AbterPhp\Framework\I18n\ITranslator;

class FileDownload extends Filters
{
    /**
     * FileDownload constructor.
     *
     * @param string|null      $tag
     * @param array            $attributes
     * @param ITranslator|null $translator
     */
    public function __construct(?string $tag = null, array $attributes = [], ITranslator $translator = null)
    {
        parent::__construct($tag, $attributes, $translator);
    }
}
