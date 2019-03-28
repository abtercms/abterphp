<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Events\Listeners;

use AbterPhp\Admin\Constant\Routes;
use AbterPhp\Framework\Events\NavigationReady;
use AbterPhp\Framework\Html\Component\ButtonFactory;
use AbterPhp\Framework\Navigation\Dropdown;
use AbterPhp\Framework\Navigation\Item;
use AbterPhp\Framework\Navigation\UserBlock;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;
use AbterPhp\Framework\Navigation\Navigation;

class NavigationBuilder
{
    const BASE_WEIGHT = 200;

    /** @var ISession */
    protected $session;

    /** @var UrlGenerator */
    protected $urlGenerator;

    /** @var ButtonFactory */
    protected $buttonFactory;

    /**
     * NavigationRegistrar constructor.
     *
     * @param ISession     $session
     * @param UrlGenerator $urlGenerator
     * @param ButtonFactory $buttonFactory
     */
    public function __construct(ISession $session, UrlGenerator $urlGenerator, ButtonFactory $buttonFactory)
    {
        $this->session      = $session;
        $this->urlGenerator = $urlGenerator;
        $this->buttonFactory = $buttonFactory;
    }

    /**
     * @param NavigationReady $event
     */
    public function handle(NavigationReady $event)
    {
        $navigation = $event->getNavigation();

        if (!$navigation->hasIntent(Navigation::INTENT_PRIMARY)) {
            return;
        }

        $this->insertFirstItem($navigation);
    }

    /**
     * @param Navigation $navigation
     */
    protected function insertFirstItem(Navigation $navigation)
    {
        $firstItem = new Item(null, [UserBlock::class]);

        $firstItem[] = $this->createUserBlock();
        $firstItem[] = $this->createDropdown();

        $navigation->addItem($firstItem, 0);
    }

    /**
     * @return UserBlock
     */
    protected function createUserBlock(): UserBlock
    {
        return new UserBlock($this->session, $this->urlGenerator);
    }

    /**
     * @return Dropdown
     */
    protected function createDropdown(): Dropdown
    {
        $text = 'framework:logout';

        $button   = $this->buttonFactory->createFromName($text, Routes::ROUTE_LOGOUT, []);

        return new Dropdown(new Item($button));
    }
}
