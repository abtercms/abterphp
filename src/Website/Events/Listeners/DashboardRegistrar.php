<?php

declare(strict_types=1);

namespace AbterPhp\Website\Events\Listeners;

use AbterPhp\Framework\Events\DashboardReady;
use AbterPhp\Framework\Html\Component\Tag;

class DashboardRegistrar
{
    const CONTENT = 'Insert dashboard component for Website module.';

    /**
     * @param DashboardReady $event
     */
    public function handle(DashboardReady $event)
    {
        $event->getDashboard()[] = new Tag(static::CONTENT, [], null, Tag::TAG_P);
    }
}
