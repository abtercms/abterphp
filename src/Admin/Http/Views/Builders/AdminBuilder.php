<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Http\Views\Builders;

use AbterPhp\Framework\Assets\AssetManager;
use AbterPhp\Framework\Constant\Env;
use AbterPhp\Framework\Constant\Session;
use AbterPhp\Framework\Navigation\Navigation;
use Opulence\Sessions\ISession;
use Opulence\Views\Factories\IViewBuilder;
use Opulence\Views\IView;

/**
 * Defines a view builder for the admin pages
 */
class AdminBuilder implements IViewBuilder
{
    /** @var ISession */
    protected $session;

    /** @var AssetManager */
    protected $assets;

    /** @var Navigation */
    protected $navigation;

    /**
     * AdminBuilder constructor.
     *
     * @param ISession     $session
     * @param AssetManager $assets
     * @param Navigation   $navigation
     */
    public function __construct(ISession $session, AssetManager $assets, Navigation $navigation)
    {
        $this->session    = $session;
        $this->assets     = $assets;
        $this->navigation = $navigation;
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

        $view->setVar('env', getenv(Env::ENV_NAME));
        $view->setVar('title', 'Admin');
        $view->setVar('username', $this->session->get(Session::USERNAME));
        $view->setVar('navigation', $this->navigation);

        return $view;
    }
}
