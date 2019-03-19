<?php

declare(strict_types=1);

namespace AbterPhp\PropellerAdmin\Events\Listeners;

use AbterPhp\Admin\Constant\Routes;
use AbterPhp\Framework\Constant\Navigation as NavConstant;
use AbterPhp\Framework\Events\NavigationReady;
use AbterPhp\Framework\Html\Component\ButtonFactory;
use AbterPhp\Framework\Html\Component\Tag;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Navigation\Navigation;
use AbterPhp\Framework\Navigation\UserBlock;
use AbterPhp\PropellerAdmin\Navigation\Header;

class NavigationRegistrar
{
    const NAVBAR_CONTAINER_CLASS = 'navbar navbar-inverse navbar-fixed-top pmd-navbar pmd-z-depth';
    const NAVBAR_CLASS           = 'container-fluid';

    const PRIMARY_PREFIX_CLASS    = 'pmd-sidebar-overlay';
    const PRIMARY_CONTAINER_CLASS = 'pmd-sidebar sidebar-default pmd-sidebar-slide-push pmd-sidebar-left pmd-sidebar-open bg-fill-darkblue sidebar-with-icons nav pmd-sidebar-nav'; // nolint
    const PRIMARY_CLASS           = 'nav pmd-sidebar-nav';

    const USER_BLOCK_CLASS = 'dropdown pmd-dropdown pmd-user-info visible-xs visible-md visible-sm visible-lg';

    const BASE_WEIGHT = 1000;
    const HEADER_WEIGHT = -100000;

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
        $nav = $event->getNavigation();

        switch ($nav->getName()) {
            case NavConstant::PRIMARY:
                $this->handlePrimary($nav);
                break;
            case NavConstant::NAVBAR:
                $this->handleNavbar($nav);
                break;
        }
    }

    /**
     * @param Navigation $navigation
     */
    protected function handlePrimary(Navigation $navigation)
    {
        // Setup navigation properly
        $navigation->setPrefix(new Tag('', [Navigation::ATTRIBUTE_CLASS => static::PRIMARY_PREFIX_CLASS]));
        $navigation->appendToAttribute(Navigation::ATTRIBUTE_CLASS, static::PRIMARY_CLASS);
        $wrapperAttribs = [
            Navigation::ATTRIBUTE_CLASS => static::PRIMARY_CONTAINER_CLASS,
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

    /**
     * @param Navigation $navigation
     */
    protected function handleNavbar(Navigation $navigation)
    {
        // Setup navigation properly
        $navigation->setTag(Tag::TAG_DIV);
        $navigation->appendToAttribute(Navigation::ATTRIBUTE_CLASS, static::NAVBAR_CLASS);
        $wrapperAttribs = [Navigation::ATTRIBUTE_CLASS => static::NAVBAR_CONTAINER_CLASS];
        $navigation->setWrapper(new Tag('', $wrapperAttribs, null, Navigation::TAG_NAV));

        // Add header
        $header = new Header($this->buttonFactory->createFromName('', Routes::ROUTE_DASHBOARD, []));
        $navigation->addItem($header, static::HEADER_WEIGHT);
    }
}
