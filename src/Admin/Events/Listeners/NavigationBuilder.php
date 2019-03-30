<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Events\Listeners;

use AbterPhp\Admin\Constant\Routes;
use AbterPhp\Framework\Events\NavigationReady;
use AbterPhp\Framework\Html\Component\ButtonFactory;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Navigation\Item;
use AbterPhp\Framework\Navigation\Navigation;

class NavigationBuilder
{
    const BASE_WEIGHT = 1000;

    /** @var ButtonFactory */
    protected $buttonFactory;

    /**
     * NavigationRegistrar constructor.
     *
     * @param ButtonFactory $buttonFactory
     */
    public function __construct(ButtonFactory $buttonFactory)
    {
        $this->buttonFactory = $buttonFactory;
    }

    /**
     * @param NavigationReady $event
     *
     * @throws \Opulence\Routing\Urls\URLException
     */
    public function handle(NavigationReady $event)
    {
        $navigation = $event->getNavigation();

        if (!$navigation->hasIntent(Navigation::INTENT_PRIMARY)) {
            return;
        }

        $this->addUser($navigation);
        $this->addUserGroup($navigation);
        $this->addLogout($navigation);
    }

    /**
     * @param Navigation $navigation
     *
     * @throws \Opulence\Routing\Urls\URLException
     */
    protected function addUser(Navigation $navigation)
    {
        $text = 'admin:users';
        $icon = 'person';

        $button   = $this->buttonFactory->createFromName($text, Routes::ROUTE_USERS, [], $icon);
        $resource = $this->getAdminResource(Routes::ROUTE_USERS);

        $navigation->addItem(new Item($button), static::BASE_WEIGHT, $resource);
    }

    /**
     * @param Navigation $navigation
     *
     * @throws \Opulence\Routing\Urls\URLException
     */
    protected function addUserGroup(Navigation $navigation)
    {
        $text = 'admin:userGroups';
        $icon = 'group';

        $button   = $this->buttonFactory->createFromName($text, Routes::ROUTE_USER_GROUPS, [], $icon);
        $resource = $this->getAdminResource(Routes::ROUTE_USER_GROUPS);

        $navigation->addItem(new Item($button), static::BASE_WEIGHT, $resource);
    }

    /**
     * @param Navigation $navigation
     *
     * @throws \Opulence\Routing\Urls\URLException
     */
    protected function addLogout(Navigation $navigation)
    {
        $text = 'admin:logout';
        $icon = 'settings_power';

        $button   = $this->buttonFactory->createFromName($text, Routes::ROUTE_LOGOUT, [], $icon);
        $resource = $this->getAdminResource(Routes::ROUTE_LOGOUT);

        $navigation->addItem(new Item($button), PHP_INT_MAX, $resource);
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