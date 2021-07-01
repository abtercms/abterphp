<?php

declare(strict_types=1);

namespace AbterPhp\Website\Routes;

use AbterPhp\Admin\Config\Routes as RoutesConfig;
use AbterPhp\Framework\Routes\IRouteConfigurator;
use AbterPhp\Website\Constant\Route as RouteConstant;
use Opulence\Routing\Router;

/** @phan-file-suppress PhanInvalidFQSENInCallable */

class WebsiteRouteConfigurator implements IRouteConfigurator
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
        $this->setWebsiteRoutes($router);
    }

    /**
     * @param \Opulence\Routing\Router $router
     */
    public function setWebsiteRoutes(Router $router)
    {
        $router->group(
            ['controllerNamespace' => 'AbterPhp\Website\Http\Controllers'],
            function (Router $router) {
                /** @see \AbterPhp\Website\Http\Controllers\Website\Index::index() */
                $router->get(
                    '/',
                    'Website\Index@index',
                    [RouteConstant::OPTION_NAME => RouteConstant::INDEX]
                );

                /** @see \AbterPhp\Website\Http\Controllers\Website\Index::fallback() */
                $router->get(
                    '/:identifier',
                    'Website\Index@fallback',
                    [
                        RouteConstant::OPTION_NAME => RouteConstant::FALLBACK,
                        RouteConstant::OPTION_VARS => [RouteConstant::VAR_ANYTHING => '[\w\d\-]+'],
                    ]
                );
            }
        );
    }
}
