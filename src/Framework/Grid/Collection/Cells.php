<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Collection;

use AbterPhp\Framework\Grid\Cell\ICell;
use AbterPhp\Framework\Html\Collection\Collection;
use AbterPhp\Framework\Html\Helper\StringHelper;
use InvalidArgumentException;
use LogicException;

class Cells extends Collection
{
    const DEFAULT_TAG = '';

    /** @var ICell[] */
    protected $components = [];

    /** @var string */
    protected $componentClass = ICell::class;
}
