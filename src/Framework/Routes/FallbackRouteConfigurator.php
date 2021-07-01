<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Routes;

use AbterPhp\Framework\Config\Routes as RoutesConfig;
use AbterPhp\Framework\Constant\Route;
use Opulence\Routing\Router;

/** @phan-file-suppress PhanInvalidFQSENInCallable */

class FallbackRouteConfigurator implements IRouteConfigurator
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
        $router->group(
            ['controllerNamespace' => 'AbterPhp\Framework\Http\Controllers'],
            function (Router $router) {
                /** @see \AbterPhp\Framework\Http\Controllers\Website\Index::notFound() */
                $router->any(
                    '/:anything',
                    'Website\Index@notFound',
                    [
                        Route::OPTION_NAME => Route::NOT_FOUND,
                        Route::OPTION_VARS => [Route::VAR_ANYTHING => '.+'],
                    ]
                );
            }
        );
    }
}
