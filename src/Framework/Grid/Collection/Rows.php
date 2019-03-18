<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Collection;

use AbterPhp\Framework\Grid\Row\IRow;
use AbterPhp\Framework\Html\Collection\Collection;

class Rows extends Collection
{
    const DEFAULT_TAG = '';

    /** @var IRow[] */
    protected $components = [];

    /** @var string */
    protected $componentClass = IRow::class;

    /**
     * Rows constructor.
     *
     * @param array       $attributes
     * @param string|null $tag
     */
    public function __construct(array $attributes = [], ?string $tag = null)
    {
        parent::__construct($attributes, null, $tag);
    }
}
