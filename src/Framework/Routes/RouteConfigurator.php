<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Routes;

use AbterPhp\Framework\Config\Routes as RoutesConfig;
use AbterPhp\Framework\Constant\Route;
use Opulence\Routing\Router;

/** @phan-file-suppress PhanInvalidFQSENInCallable */

class RouteConfigurator implements IRouteConfigurator
{
    protected RoutesConfig $config;

    /**
     * Routes constructor.
     *
     * @param RoutesConfig $config
     */
    public function __construct(RoutesConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param Router $router
     */
    public function setRoutes(Router $router)
    {
        $this->setAssetRoutes($router);
    }

    /**
     * @param Router $router
     */
    protected function setAssetRoutes(Router $router)
    {
        $config = $this->config;

        $router->group(
            ['controllerNamespace' => 'AbterPhp\Framework\Http\Controllers'],
            function (Router $router) use ($config) {
                /** @see \AbterPhp\Framework\Http\Controllers\Website\Assets::asset() */
                $router->get(
                    $config->getAssetsPath(),
                    'Website\Assets@asset',
                    [
                        Route::OPTION_NAME => Route::ASSET_CACHE,
                        Route::OPTION_VARS => [Route::VAR_PATH => '(.+)\.([\w\d\?]+)'],
                    ]
                );

                /** @see \AbterPhp\Framework\Http\Controllers\Website\Assets::asset() */
                $router->get(
                    '/:path',
                    'Website\Assets@asset',
                    [
                        Route::OPTION_NAME => Route::ASSET,
                        Route::OPTION_VARS => [Route::VAR_PATH => '(.+)\.([\w\d\?]+)'],
                    ]
                );
            }
        );
    }
}
