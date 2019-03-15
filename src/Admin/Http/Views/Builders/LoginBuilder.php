<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Http\Views\Builders;

use AbterPhp\Framework\Assets\AssetManager;
use AbterPhp\Framework\Constant\Env;
use Opulence\Views\Factories\IViewBuilder;
use Opulence\Views\IView;

/**
 * Defines the view builder for the login page
 */
class LoginBuilder implements IViewBuilder
{
    /** @var AssetManager */
    protected $assets;

    /**
     * AdminBuilder constructor.
     *
     * @param AssetManager $assets
     */
    public function __construct(AssetManager $assets)
    {
        $this->assets = $assets;
    }

    /**
     * @inheritdoc
     */
    public function build(IView $view): IView
    {
        $this->assets->addCss('admin-layout', '/admin-assets/vendor/bootstrap/bootstrap.min.css');
        $this->assets->addCss('admin-layout', '/admin-assets/vendor/propeller/css/propeller.min.css');
        $this->assets->addCss('admin-layout', '/admin-assets/themes/css/propeller-theme.css');
        $this->assets->addCss('admin-layout', '/admin-assets/themes/css/propeller-admin.css');
        $this->assets->addCss('admin-layout', '/admin-assets/css/style.css');

        $this->assets->addJs('admin-layout-header', '/admin-assets/vendor/jquery/jquery.min.js');
        $this->assets->addJs('admin-layout-footer', '/admin-assets/vendor/bootstrap/bootstrap.min.js');
        $this->assets->addJs('admin-layout-footer', '/admin-assets/vendor/propeller/js/propeller.min.js');
        $this->assets->addJs('admin-layout-footer', '/admin-assets/js/alerts.js');

        $this->assets->addJs('admin-login', '/admin-assets/vendor/sha3/sha3.min.js');
        $this->assets->addJs('admin-login', '/admin-assets/js/login.js');

        $view->setVar('env', getenv(Env::ENV_NAME));
        $view->setVar('title', 'Login');
        $view->setVar('page', '');
        $view->setVar('pageHeader', '');
        $view->setVar('pageFooter', '');

        return $view;
    }
}
