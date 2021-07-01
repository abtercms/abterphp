<?php

namespace AbterPhp\Website\Bootstrappers\Http\Controllers\Website;

use AbterPhp\Framework\Assets\AssetManager;
use AbterPhp\Framework\Environments\Environment;
use AbterPhp\Framework\Session\FlashService;
use AbterPhp\Website\Constant\Env;
use AbterPhp\Website\Http\Controllers\Website\Index;
use AbterPhp\Website\Service\Website\Index as IndexService;
use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\IContainer;
use Opulence\Ioc\IocException;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;
use Psr\Log\LoggerInterface;

class IndexBootstrapper extends Bootstrapper
{

    /**
     * Registers any bindings to the IoC container
     *
     * @param IContainer $container The IoC container to bind to
     *
     * @throws IocException
     */
    public function registerBindings(IContainer $container)
    {
        $flashService     = $container->resolve(FlashService::class);
        $logger           = $container->resolve(LoggerInterface::class);
        $session          = $container->resolve(ISession::class);
        $indexService     = $container->resolve(IndexService::class);
        $urlGenerator     = $container->resolve(UrlGenerator::class);
        $assetManager     = $container->resolve(AssetManager::class);
        $websiteBaseUrl   = Environment::mustGetVar(Env::WEBSITE_BASE_URL);
        $websiteSiteTitle = Environment::mustGetVar(Env::WEBSITE_SITE_TITLE);

        $indexController = new Index(
            $flashService,
            $logger,
            $session,
            $indexService,
            $urlGenerator,
            $assetManager,
            $websiteBaseUrl,
            $websiteSiteTitle
        );

        $container->bindInstance(Index::class, $indexController);
    }
}
