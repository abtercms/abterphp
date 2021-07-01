<?php

declare(strict_types=1);

namespace AbterPhp\PropellerAdmin\Events\Listeners;

use AbterPhp\Framework\Events\NavigationReady;
use AbterPhp\Framework\Navigation\Navigation;
use AbterPhp\PropellerAdmin\Decorator\General;
use AbterPhp\PropellerAdmin\Decorator\Navigation\Navbar;
use AbterPhp\PropellerAdmin\Decorator\Navigation\Primary;

class NavigationBuilder
{
    protected Primary $primaryDecorator;

    protected Navbar $navbarDecorator;

    protected General $generalDecorator;

    /**
     * NavigationDecorator constructor.
     *
     * @param Primary $primaryDecorator
     * @param Navbar  $navbarDecorator
     * @param General $generalDecorator
     */
    public function __construct(Primary $primaryDecorator, Navbar $navbarDecorator, General $generalDecorator)
    {
        $this->primaryDecorator = $primaryDecorator;
        $this->navbarDecorator  = $navbarDecorator;
        $this->generalDecorator = $generalDecorator;
    }

    /**
     * @param NavigationReady $event
     */
    public function handle(NavigationReady $event)
    {
        $navigation = $event->getNavigation();

        $nodes = array_merge([$navigation], $navigation->getExtendedNodes());

        $this->generalDecorator->init()->decorate($nodes);

        if ($navigation->hasIntent(Navigation::INTENT_PRIMARY)) {
            $this->primaryDecorator->init()->decorate($nodes);
        } elseif ($navigation->hasIntent(Navigation::INTENT_NAVBAR)) {
            $this->navbarDecorator->init()->decorate($nodes);
        }
    }
}
