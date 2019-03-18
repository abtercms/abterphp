<?php

namespace AbterPhp\Admin\Bootstrappers\Http\Views;

use AbterPhp\Admin\Http\Views\Builders\AdminBuilder;
use AbterPhp\Admin\Http\Views\Builders\HtmlErrorBuilder;
use AbterPhp\Admin\Http\Views\Builders\LoginBuilder;
use AbterPhp\Framework\Assets\AssetManager;
use AbterPhp\Framework\Constant\Dependencies;
use AbterPhp\Framework\Navigation\Navigation;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\IContainer;
use Opulence\Sessions\ISession;
use Opulence\Views\Factories\IViewFactory;
use Opulence\Views\IView;

/**
 * Defines the view builders bootstrapper
 */
class BuildersBootstrapper extends Bootstrapper
{
    /**
     * @inheritdoc
     */
    public function registerBindings(IContainer $container)
    {
        /** @var IViewFactory $viewFactory */
        $viewFactory = $container->resolve(IViewFactory::class);

        $viewFactory->registerBuilder(
            'layouts/backend/default',
            function (IView $view) use ($container) {
                /** @var ISession $session */
                $session = $container->resolve(ISession::class);

                /** @var AssetManager $assets */
                $assets = $container->resolve(AssetManager::class);

                /** @var IEventDispatcher $eventDispatcher */
                $eventDispatcher = $container->resolve(IEventDispatcher::class);

                /** @var Navigation $primaryNavigation */
                $primaryNavigation = $container->resolve(Dependencies::NAVIGATION_PRIMARY);

                /** @see AdminBuilder::build() */
                return (new AdminBuilder($session, $assets, $eventDispatcher, $primaryNavigation))->build($view);
            }
        );
        $viewFactory->registerBuilder(
            'layouts/backend/login',
            function (IView $view) use ($container) {
                /** @var AssetManager $assets */
                $assets = $container->resolve(AssetManager::class);

                /** @var IEventDispatcher $eventDispatcher */
                $eventDispatcher = $container->resolve(IEventDispatcher::class);

                /** @see AdminBuilder::build() */
                return (new LoginBuilder($assets, $eventDispatcher))->build($view);
            }
        );
        $viewFactory->registerBuilder(
            'layouts/backend/empty',
            function (IView $view) use ($container) {
                /** @var ISession $session */
                $session = $container->resolve(ISession::class);

                /** @var AssetManager $assets */
                $assets = $container->resolve(AssetManager::class);

                /** @var IEventDispatcher $eventDispatcher */
                $eventDispatcher = $container->resolve(IEventDispatcher::class);

                /** @see AdminBuilder::build() */
                return (new AdminBuilder($session, $assets, $eventDispatcher, null))->build($view);
            }
        );
        $viewFactory->registerBuilder(
            'errors/html/Error',
            function (IView $view) {
                /** @see HtmlErrorBuilder::build() */
                return (new HtmlErrorBuilder())->build($view);
            }
        );
    }
}
