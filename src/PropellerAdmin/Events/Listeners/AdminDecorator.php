<?php

declare(strict_types=1);

namespace AbterPhp\PropellerAdmin\Events\Listeners;

use AbterPhp\Admin\Events\AdminReady;
use AbterPhp\Framework\Assets\AssetManager;
use AbterPhp\Framework\Constant\View;

class AdminDecorator
{
    /** @var AssetManager */
    protected $assets;

    /** @var string */
    protected $header;

    /** @var string */
    protected $footer;

    /**
     * AdminDecorator constructor.
     *
     * @param AssetManager $assetManager
     * @param string       $header
     * @param string       $footer
     */
    public function __construct(AssetManager $assetManager, string $header, string $footer)
    {
        $this->assets = $assetManager;
        $this->header = $header;
        $this->footer = $footer;
    }

    /**
     * @param AdminReady $event
     */
    public function handle(AdminReady $event)
    {
        $view = $event->getView();

        $origHeader = $view->hasVar(View::PRE_HEADER) ? (string)$view->getVar('preHeader') : '';
        $origFooter = $view->hasVar(View::PRE_FOOTER) ? (string)$view->getVar('preFooter') : '';

        $view->setVar(View::PRE_HEADER, $this->header . $origHeader);
        $view->setVar(View::PRE_FOOTER, $this->footer . $origFooter);

        $this->assets->addCss(View::ASSET_DEFAULT, '/admin-assets/vendor/propeller/css/propeller.min.css');
        $this->assets->addCss(View::ASSET_DEFAULT, '/admin-assets/themes/css/propeller-theme.css');
        $this->assets->addCss(View::ASSET_DEFAULT, '/admin-assets/themes/css/propeller-admin.css');
        $this->assets->addCss(View::ASSET_DEFAULT, '/admin-assets/css/style.css');

        $this->assets->addJs(View::ASSET_FOOTER, '/admin-assets/vendor/propeller/js/propeller.min.js');
    }
}
