<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Events\Listeners;

use AbterPhp\Admin\Constant\Routes;
use AbterPhp\Framework\Constant\Navigation as NavConstant;
use AbterPhp\Framework\Events\NavigationReady;
use AbterPhp\Framework\Html\Component\ButtonFactory;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Navigation\Item;
use AbterPhp\Framework\Navigation\Navigation;

class NavigationRegistrar
{
    const BASE_WEIGHT = 1000;

    /** @var ITranslator */
    protected $translator;

    /** @var ButtonFactory */
    protected $buttonFactory;

    /**
     * NavigationRegistrar constructor.
     *
     * @param ITranslator   $translator
     * @param ButtonFactory $buttonFactory
     */
    public function __construct(ITranslator $translator, ButtonFactory $buttonFactory)
    {
        $this->translator    = $translator;
        $this->buttonFactory = $buttonFactory;
    }

    /**
     * @param NavigationReady $event
     *
     * @throws \Opulence\Routing\Urls\URLException
     */
    public function handle(NavigationReady $event)
    {
        if ($event->getNavigation()->getName() !== NavConstant::PRIMARY) {
            return;
        }

        $navigation = $event->getNavigation();

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
        $text     = $this->translator->translate('admin:users');
        $button   = $this->buttonFactory->createFromName($text, Routes::ROUTE_USERS, [], 'person');
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
        $text     = $this->translator->translate('admin:userGroups');
        $button   = $this->buttonFactory->createFromName($text, Routes::ROUTE_USER_GROUPS, [], 'group');
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
        $text     = $this->translator->translate('admin:logout');
        $button   = $this->buttonFactory->createFromName($text, Routes::ROUTE_LOGOUT, [], 'settings_power');
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
