<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Routes;

use AbterPhp\Admin\Config\Routes as RoutesConfig;
use AbterPhp\Admin\Constant\Route as RouteConstant;
use AbterPhp\Admin\Http\Middleware\Api;
use AbterPhp\Admin\Http\Controllers\Api\Index;
use AbterPhp\Framework\Routes\IRouteConfigurator;
use Opulence\Routing\Router;

/**
 * @phan-file-suppress PhanUnreferencedUseNormal
 * @phan-file-suppress PhanInvalidFQSENInCallable
 */
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
        $config = $this->config;

        $router->group(
            ['controllerNamespace' => 'AbterPhp\Admin\\Http\\Controllers'],
            function (Router $router) use ($config) {
                $router->group(
                    [
                        'path'       => $config->getApiBasePath(),
                        'middleware' => [
                            Api::class,
                        ],
                    ],
                    function (Router $router) {
                        /** @see Index::notFound() */
                        $router->any(
                            '/:anything',
                            'Api\Index@notFound',
                            [
                                RouteConstant::OPTION_VARS => [RouteConstant::VAR_ANYTHING => '.+'],
                            ]
                        );
                    }
                );
            }
        );
    }
}
