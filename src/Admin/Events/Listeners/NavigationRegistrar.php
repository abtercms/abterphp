<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Events\Listeners;

use AbterPhp\Admin\Constant\Routes;
use AbterPhp\Framework\Constant\Dependencies;
use AbterPhp\Framework\Events\NavigationReady;
use AbterPhp\Framework\Html\Component\IComponent;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Navigation\Navigation;

class NavigationRegistrar
{
    const BASE_WEIGHT = 1000;

    const CONTENT_TEMPLATE = '<i class="material-icons media-left media-middle">%s</i> 
        <span class="media-body">%s</span>';

    /** @var ITranslator */
    protected $translator;

    /**
     * NavigationRegistrar constructor.
     *
     * @param ITranslator $translator
     */
    public function __construct(ITranslator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param NavigationReady $event
     *
     * @throws \Opulence\Routing\Urls\URLException
     */
    public function register(NavigationReady $event)
    {
        if ($event->getNavigation()->getName() !== Dependencies::NAVIGATION_PRIMARY) {
            return;
        }

        $navigation = $event->getNavigation();

        $navigation->appendToAttribute(Navigation::ATTRIBUTE_CLASS, 'nav pmd-sidebar-nav');

        $this->addUser($navigation);
        $this->addUserGroup($navigation);
        $this->addLogout($navigation);
    }

    /**
     * @param Navigation $navigation
     *
     * @return IComponent|null
     * @throws \Opulence\Routing\Urls\URLException
     */
    protected function addUser(Navigation $navigation): ?IComponent
    {
        $resource  = $this->getAdminResource(Routes::ROUTE_USERS);
        $component = sprintf(
            static::CONTENT_TEMPLATE,
            'person',
            $this->translator->translate('admin:users')
        );

        return $navigation->createFromName($component, Routes::ROUTE_USERS, [], static::BASE_WEIGHT, $resource);
    }

    /**
     * @param Navigation $navigation
     *
     * @return IComponent|null
     * @throws \Opulence\Routing\Urls\URLException
     */
    protected function addUserGroup(Navigation $navigation): ?IComponent
    {
        $resource  = $this->getAdminResource(Routes::ROUTE_USER_GROUPS);
        $component = sprintf(
            static::CONTENT_TEMPLATE,
            'group',
            $this->translator->translate('admin:userGroups')
        );

        return $navigation->createFromName($component, Routes::ROUTE_USER_GROUPS, [], static::BASE_WEIGHT, $resource);
    }

    /**
     * @param Navigation $navigation
     *
     * @return IComponent|null
     * @throws \Opulence\Routing\Urls\URLException
     */
    protected function addLogout(Navigation $navigation): ?IComponent
    {
        $component = sprintf(
            static::CONTENT_TEMPLATE,
            'settings_power',
            $this->translator->translate('admin:logout')
        );

        return $navigation->createFromName($component, Routes::ROUTE_LOGOUT, [], PHP_INT_MAX);
    }

    /**
     * @param string $resource
     *
     * @return string
     */
    protected function getAdminResource(string $resource): string
    {
        return sprintf('admin_resource_%s', $resource);
    }
}
