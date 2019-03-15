<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Dashboard;

use AbterPhp\Framework\Html\Collection\Collection;

class Dashboard extends Collection
{
    /** @var string[] */
    protected $attributes = [
        self::ATTRIBUTE_CLASS => 'dashboard-container',
    ];
}
