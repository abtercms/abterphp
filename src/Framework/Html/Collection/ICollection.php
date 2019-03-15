<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html\Collection;

use AbterPhp\Framework\Html\Component\IComponent;
use ArrayAccess;
use Countable;
use Iterator;

interface ICollection extends ArrayAccess, Countable, Iterator, IComponent
{

}
