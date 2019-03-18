<?php

declare(strict_types=1);

namespace AbterPhp\Propeller\Events\Listeners;

use AbterPhp\Framework\Events\NavigationReady;

class NavigationRegistrar
{
    /**
     * @param NavigationReady $event
     *
     * @throws \Opulence\Routing\Urls\URLException
     */
    public function register(NavigationReady $event)
    {
    }
}
