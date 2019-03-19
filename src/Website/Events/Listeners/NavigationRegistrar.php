<?php

declare(strict_types=1);

namespace AbterPhp\Website\Events\Listeners;

use AbterPhp\Framework\Constant\Navigation as NavConstant;
use AbterPhp\Framework\Events\NavigationReady;
use AbterPhp\Framework\Html\Component\IComponent;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Navigation\Navigation;
use AbterPhp\Website\Constant\Routes;

class NavigationRegistrar
{
    const BASE_WEIGHT = 400;

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
        if ($event->getNavigation()->getName() !== NavConstant::PRIMARY) {
            return;
        }

        $navigation = $event->getNavigation();

        $navigation->appendToAttribute(Navigation::ATTRIBUTE_CLASS, 'nav pmd-sidebar-nav');

        $this->addPage($navigation);
        $this->addPageLayout($navigation);
        $this->addBlock($navigation);
        $this->addBlockLayout($navigation);
    }

    /**
     * @param Navigation $navigation
     *
     * @return IComponent|null
     * @throws \Opulence\Routing\Urls\URLException
     */
    protected function addPage(Navigation $navigation): ?IComponent
    {
        $resource  = $this->getAdminResource(Routes::ROUTE_PAGES);
        $component = sprintf(
            static::CONTENT_TEMPLATE,
            'text_format',
            $this->translator->translate('pages:pages')
        );

        return $navigation->createFromName($component, Routes::ROUTE_PAGES, [], static::BASE_WEIGHT, $resource);
    }

    /**
     * @param Navigation $navigation
     *
     * @return IComponent|null
     * @throws \Opulence\Routing\Urls\URLException
     */
    protected function addPageLayout(Navigation $navigation): ?IComponent
    {
        $resource  = $this->getAdminResource(Routes::ROUTE_PAGE_LAYOUTS);
        $component = sprintf(
            static::CONTENT_TEMPLATE,
            'view_quilt',
            $this->translator->translate('pages:pageLayouts')
        );

        return $navigation->createFromName($component, Routes::ROUTE_PAGE_LAYOUTS, [], static::BASE_WEIGHT, $resource);
    }

    /**
     * @param Navigation $navigation
     *
     * @return IComponent|null
     * @throws \Opulence\Routing\Urls\URLException
     */
    protected function addBlock(Navigation $navigation): ?IComponent
    {
        $resource  = $this->getAdminResource(Routes::ROUTE_BLOCKS);
        $component = sprintf(
            static::CONTENT_TEMPLATE,
            'view_module',
            $this->translator->translate('pages:blocks')
        );

        return $navigation->createFromName($component, Routes::ROUTE_BLOCKS, [], static::BASE_WEIGHT, $resource);
    }

    /**
     * @param Navigation $navigation
     *
     * @return IComponent|null
     * @throws \Opulence\Routing\Urls\URLException
     */
    protected function addBlockLayout(Navigation $navigation): ?IComponent
    {
        $resource  = $this->getAdminResource(Routes::ROUTE_BLOCK_LAYOUTS);
        $component = sprintf(
            static::CONTENT_TEMPLATE,
            'view_quilt',
            $this->translator->translate('pages:blockLayouts')
        );

        return $navigation->createFromName($component, Routes::ROUTE_BLOCK_LAYOUTS, [], static::BASE_WEIGHT, $resource);
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
