<?php

declare(strict_types=1);

namespace AbterPhp\Website\Http\Views\Builders;

use AbterPhp\Website\Constant\Events;
use AbterPhp\Website\Events\WebsiteReady;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Views\Factories\IViewBuilder;
use Opulence\Views\IView;

/**
 * Defines the master view builder
 */
class WebsiteBuilder implements IViewBuilder
{
    /** @var IEventDispatcher */
    protected $eventDispatcher;

    /**
     * WebsiteBuilder constructor.
     *
     * @param IEventDispatcher $eventDispatcher
     */
    public function __construct(IEventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @inheritdoc
     */
    public function build(IView $view): IView
    {
        $view->setVar('title', '');
        $view->setVar('metaKeywords', []);
        $view->setVar('metaDescription', '');
        $view->setVar('metaAuthor', '');
        $view->setVar('metaCopyright', '');
        $view->setVar('metaRobots', '');
        $view->setVar('metaOGImage', '');
        $view->setVar('metaOGDescription', '');
        $view->setVar('metaOGTitle', '');
        $view->setVar('siteTitle', '');
        $view->setVar('homepageUrl', '');
        $view->setVar('pageUrl', '');
        $view->setVar('layout', '');
        $view->setVar('page', '');
        $view->setVar('preHeader', '');
        $view->setVar('header', '');
        $view->setVar('postHeader', '');
        $view->setVar('preFooter', '');
        $view->setVar('footer', '');
        $view->setVar('postFooter', '');

        $this->eventDispatcher->dispatch(Events::WEBSITE_READY, new WebsiteReady($view));

        return $view;
    }
}
