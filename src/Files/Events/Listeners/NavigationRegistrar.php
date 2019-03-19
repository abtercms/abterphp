<?php

declare(strict_types=1);

namespace AbterPhp\Files\Events\Listeners;

use AbterPhp\Files\Constant\Routes;
use AbterPhp\Framework\Constant\Navigation as NavConstant;
use AbterPhp\Framework\Events\NavigationReady;
use AbterPhp\Framework\Html\Component\IComponent;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Navigation\Navigation;

class NavigationRegistrar
{
    const BASE_WEIGHT = 600;

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
    public function handle(NavigationReady $event)
    {
        if ($event->getNavigation()->getName() !== NavConstant::PRIMARY) {
            return;
        }

        $navigation = $event->getNavigation();

        $navigation->appendToAttribute(Navigation::ATTRIBUTE_CLASS, 'nav pmd-sidebar-nav');

        $this->addFileCategories($navigation);
        $this->addFiles($navigation);
        $this->addFileDownloads($navigation);
    }

    /**
     * @param Navigation $navigation
     *
     * @return IComponent|null
     * @throws \Opulence\Routing\Urls\URLException
     */
    protected function addFileCategories(Navigation $navigation): ?IComponent
    {
        $resource  = $this->getAdminResource(Routes::ROUTE_FILE_CATEGORIES);
        $component = sprintf(
            static::CONTENT_TEMPLATE,
            'folder',
            $this->translator->translate('files:fileCategories')
        );

        return $navigation->createFromName(
            $component,
            Routes::ROUTE_FILE_CATEGORIES,
            [],
            static::BASE_WEIGHT,
            $resource
        );
    }

    /**
     * @param Navigation $navigation
     *
     * @return IComponent|null
     * @throws \Opulence\Routing\Urls\URLException
     */
    protected function addFiles(Navigation $navigation): ?IComponent
    {
        $resource  = $this->getAdminResource(Routes::ROUTE_FILES);
        $component = sprintf(
            static::CONTENT_TEMPLATE,
            'attachment',
            $this->translator->translate('files:files')
        );

        return $navigation->createFromName($component, Routes::ROUTE_FILES, [], static::BASE_WEIGHT, $resource);
    }

    /**
     * @param Navigation $navigation
     *
     * @return IComponent|null
     * @throws \Opulence\Routing\Urls\URLException
     */
    protected function addFileDownloads(Navigation $navigation): ?IComponent
    {
        $resource  = $this->getAdminResource(Routes::ROUTE_FILE_DOWNLOADS);
        $component = sprintf(
            static::CONTENT_TEMPLATE,
            'file_download',
            $this->translator->translate('files:fileDownloads')
        );

        return $navigation->createFromName(
            $component,
            Routes::ROUTE_FILE_DOWNLOADS,
            [],
            static::BASE_WEIGHT,
            $resource
        );
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
