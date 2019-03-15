<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Events\Listeners;

use AbterPhp\Framework\Events\DashboardReady;

class DashboardRegistrar
{
    /**
     * @param DashboardReady $event
     */
    public function handle(DashboardReady $event)
    {
        $event->getDashboard()[] = '<p>Insert dashboard component for Admin module.</p>';
    }
}
