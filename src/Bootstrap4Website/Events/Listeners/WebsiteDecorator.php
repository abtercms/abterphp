<?php

declare(strict_types=1);

namespace AbterPhp\Bootstrap4Website\Events\Listeners;

use AbterPhp\Website\Events\WebsiteReady;

class WebsiteDecorator
{
    /** @var string */
    protected $header;

    /** @var string */
    protected $footer;

    /**
     * WebsiteDecorator constructor.
     *
     * @param string $header
     * @param string $footer
     */
    public function __construct(string $header, string $footer)
    {
        $this->header = $header;
        $this->footer = $footer;
    }

    /**
     * @param WebsiteReady $event
     */
    public function handle(WebsiteReady $event)
    {
        $view = $event->getView();

        $origHeader = $view->hasVar('preHeader') ? (string)$view->getVar('preHeader') : '';
        $origFooter = $view->hasVar('preFooter') ? (string)$view->getVar('preFooter') : '';

        $view->setVar('preHeader', $this->header . $origHeader);
        $view->setVar('preFooter', $this->footer . $origFooter);
    }
}
