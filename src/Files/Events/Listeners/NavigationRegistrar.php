<?php

declare(strict_types=1);

namespace AbterPhp\Files\Events\Listeners;

use AbterPhp\Files\Constant\Routes;
use AbterPhp\Framework\Constant\Navigation as NavConstant;
use AbterPhp\Framework\Events\NavigationReady;
use AbterPhp\Framework\Html\ButtonFactory;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Navigation\Item;
use AbterPhp\Framework\Navigation\Navigation;

class NavigationRegistrar
{
    const BASE_WEIGHT = 600;

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
    public function handle(NavigationReady $event)
    {
        if ($event->getNavigation()->getName() !== NavConstant::PRIMARY) {
            return;
        }

        $navigation = $event->getNavigation();

        $this->addFileCategories($navigation);
        $this->addFiles($navigation);
        $this->addFileDownloads($navigation);
    }

    /**
     * @param Navigation $navigation
     */
    protected function addFileCategories(Navigation $navigation)
    {
        $text     = $this->translator->translate('files:fileCategories');
        $button   = $this->buttonFactory->createFromName($text, Routes::ROUTE_FILE_CATEGORIES, [], 'folder');
        $resource = $this->getAdminResource(Routes::ROUTE_FILE_CATEGORIES);

        $navigation->addItem(new Item($button), static::BASE_WEIGHT, $resource);
    }

    /**
     * @param Navigation $navigation
     */
    protected function addFiles(Navigation $navigation)
    {
        $text     = $this->translator->translate('files:files');
        $button   = $this->buttonFactory->createFromName($text, Routes::ROUTE_FILES, [], 'attachment');
        $resource = $this->getAdminResource(Routes::ROUTE_FILES);

        $navigation->addItem(new Item($button), static::BASE_WEIGHT, $resource);
    }

    /**
     * @param Navigation $navigation
     */
    protected function addFileDownloads(Navigation $navigation)
    {
        $text     = $this->translator->translate('files:fileDownloads');
        $button   = $this->buttonFactory->createFromName($text, Routes::ROUTE_FILE_DOWNLOADS, [], 'file_download');
        $resource = $this->getAdminResource(Routes::ROUTE_FILE_DOWNLOADS);

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
