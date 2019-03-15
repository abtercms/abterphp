<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Events\Listeners;

use AbterPhp\Framework\Constant\Dependencies;
use AbterPhp\Framework\Events\NavigationReady;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Navigation\UserBlock;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;

class NavigationRegistrar
{
    const BASE_WEIGHT = 200;

    /**
     * NavigationRegistrar constructor.
     *
     * @param ISession     $session
     * @param ITranslator  $translator
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(ISession $session, ITranslator $translator, UrlGenerator $urlGenerator)
    {
        $this->session      = $session;
        $this->translator   = $translator;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param NavigationReady $event
     */
    public function register(NavigationReady $event)
    {
        if ($event->getNavigation()->getName() !== Dependencies::NAVIGATION_PRIMARY) {
            return;
        }
        $component = new UserBlock($this->session, $this->translator, $this->urlGenerator);

        $nav = $event->getNavigation();
        $nav->addItem($component, 0);
    }
}
