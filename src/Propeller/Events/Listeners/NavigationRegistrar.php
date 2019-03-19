<?php

declare(strict_types=1);

namespace AbterPhp\Propeller\Events\Listeners;

use AbterPhp\Framework\Constant\Navigation as NavConstant;
use AbterPhp\Framework\Events\NavigationReady;
use AbterPhp\Framework\Html\Component\Tag;
use AbterPhp\Framework\Navigation\Navigation;
use AbterPhp\Framework\Navigation\UserBlock;

class NavigationRegistrar
{
    const SIDEBAR_PREFIX_CLASS = 'pmd-sidebar-overlay';
    const SIDEBAR_CLASS        = 'pmd-sidebar sidebar-default pmd-sidebar-slide-push pmd-sidebar-left pmd-sidebar-open bg-fill-darkblue sidebar-with-icons nav pmd-sidebar-nav'; // nolint
    const UL_CLASS             = 'nav pmd-sidebar-nav';
    const USER_BLOCK_CLASS     = 'dropdown pmd-dropdown pmd-user-info visible-xs visible-md visible-sm visible-lg';

    /**
     * @param NavigationReady $event
     *
     * @throws \Opulence\Routing\Urls\URLException
     */
    public function handle(NavigationReady $event)
    {
        $nav = $event->getNavigation();

        switch ($nav->getName()) {
            case NavConstant::PRIMARY:
                $this->handlePrimary($nav);
                break;
        }
    }

    /**
     * @param Navigation $navigation
     */
    protected function handlePrimary(Navigation $navigation)
    {
        // Add overlay as a prefix
        $navigation->setPrefix(new Tag('', [Navigation::ATTRIBUTE_CLASS => static::SIDEBAR_PREFIX_CLASS]));

        // Add navigation classes
        $navigation->appendToAttribute(Navigation::ATTRIBUTE_CLASS, static::UL_CLASS);

        // Setup sidebar properly
        $wrapperAttribs = [
            Navigation::ATTRIBUTE_CLASS => static::SIDEBAR_CLASS,
            Navigation::ATTRIBUTE_ROLE  => Navigation::ROLE_NAVIGATION,
        ];
        $navigation->setWrapper(new Tag('', $wrapperAttribs, null, Navigation::TAG_ASIDE));

        // Handle items
        foreach ($navigation as $item) {
            if ($item instanceof UserBlock) {
                $item->appendToAttribute(Navigation::ATTRIBUTE_CLASS, static::USER_BLOCK_CLASS);
            }
        }
    }
}
