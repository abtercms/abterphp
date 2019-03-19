<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Bootstrappers\Navigation;

use AbterPhp\Framework\Constant\Navigation as NavConstant;
use AbterPhp\Framework\Constant\Event;
use AbterPhp\Framework\Constant\Session;
use AbterPhp\Framework\Events\NavigationReady;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Navigation\Navigation;
use Casbin\Enforcer;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\Bootstrappers\ILazyBootstrapper;
use Opulence\Ioc\IContainer;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;

class NavigationBootstrapper extends Bootstrapper implements ILazyBootstrapper
{
    /** @var array */
    protected $bindings = [
        NavConstant::NAVBAR,
        NavConstant::PRIMARY
    ];

    /**
     * @return array
     */
    public function getBindings(): array
    {
        return $this->bindings;
    }

    /**
     * @param IContainer $container
     *
     * @throws \Opulence\Ioc\IocException
     */
    public function registerBindings(IContainer $container)
    {
        foreach ($this->bindings as $name) {
            $navigation = $this->createNavigation($container, $name);

            $container->bindInstance($name, $navigation);

            $this->prepareNavigation($container, $navigation);
        }
    }

    /**
     * @param IContainer $container
     * @param string     $navigationName
     *
     * @return Navigation
     * @throws \Opulence\Ioc\IocException
     */
    protected function createNavigation(IContainer $container, string $navigationName): Navigation
    {
        $session = $container->resolve(ISession::class);

        $username = $session->has(Session::USERNAME) ? $session->get(Session::USERNAME) : '';

        $translator   = $container->resolve(ITranslator::class);
        $enforcer     = $container->resolve(Enforcer::class);

        $navigation = new Navigation(
            $navigationName,
            $translator,
            $username,
            [],
            $enforcer
        );

        return $navigation;
    }

    /**
     * @param IContainer $container
     * @param Navigation $navigation
     *
     * @throws \Opulence\Ioc\IocException
     */
    protected function prepareNavigation(IContainer $container, Navigation $navigation)
    {
        /** @var IEventDispatcher $eventDispatcher */
        $eventDispatcher = $container->resolve(IEventDispatcher::class);

        $eventDispatcher->dispatch(Event::NAVIGATION_READY, new NavigationReady($navigation));
    }
}
