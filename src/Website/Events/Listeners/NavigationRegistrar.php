<?php

declare(strict_types=1);

namespace AbterPhp\Website\Events\Listeners;

use AbterPhp\Framework\Constant\Navigation as NavConstant;
use AbterPhp\Framework\Events\NavigationReady;
use AbterPhp\Framework\Html\Component\ButtonFactory;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Navigation\Item;
use AbterPhp\Framework\Navigation\Navigation;
use AbterPhp\Website\Constant\Routes;

class NavigationRegistrar
{
    const BASE_WEIGHT = 400;

    const CONTENT_TEMPLATE = '<i class="material-icons media-left media-middle">%s</i>
        <span class="media-body">%s</span>';

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
     */
    public function register(NavigationReady $event)
    {
        if ($event->getNavigation()->getName() !== NavConstant::PRIMARY) {
            return;
        }

        $navigation = $event->getNavigation();

        $this->addPage($navigation);
        $this->addPageLayout($navigation);
        $this->addBlock($navigation);
        $this->addBlockLayout($navigation);
    }

    /**
     * @param Navigation $navigation
     */
    protected function addPage(Navigation $navigation)
    {
        $text     = $this->translator->translate('pages:pages');
        $button   = $this->buttonFactory->createFromName($text, Routes::ROUTE_PAGES, [], 'text_format');
        $resource = $this->getAdminResource(Routes::ROUTE_PAGES);

        $navigation->addItem(new Item($button), static::BASE_WEIGHT, $resource);
    }

    /**
     * @param Navigation $navigation
     */
    protected function addPageLayout(Navigation $navigation)
    {
        $text     = $this->translator->translate('pages:pageLayouts');
        $button   = $this->buttonFactory->createFromName($text, Routes::ROUTE_PAGE_LAYOUTS, [], 'view_quilt');
        $resource = $this->getAdminResource(Routes::ROUTE_PAGE_LAYOUTS);

        $navigation->addItem(new Item($button), static::BASE_WEIGHT, $resource);
    }

    /**
     * @param Navigation $navigation
     */
    protected function addBlock(Navigation $navigation)
    {
        $text     = $this->translator->translate('pages:blocks');
        $button   = $this->buttonFactory->createFromName($text, Routes::ROUTE_BLOCKS, [], 'view_module');
        $resource = $this->getAdminResource(Routes::ROUTE_BLOCKS);

        $navigation->addItem(new Item($button), static::BASE_WEIGHT, $resource);
    }

    /**
     * @param Navigation $navigation
     */
    protected function addBlockLayout(Navigation $navigation)
    {
        $text     = $this->translator->translate('pages:blockLayouts');
        $button   = $this->buttonFactory->createFromName($text, Routes::ROUTE_BLOCK_LAYOUTS, [], 'view_quilt');
        $resource = $this->getAdminResource(Routes::ROUTE_BLOCK_LAYOUTS);

        $navigation->addItem(new Item($button), static::BASE_WEIGHT, $resource);
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
