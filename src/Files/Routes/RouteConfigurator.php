<?php

declare(strict_types=1);

namespace AbterPhp\Files\Routes;

use AbterPhp\Admin\Config\Routes as RoutesConfig;
use AbterPhp\Admin\Http\Middleware\Api;
use AbterPhp\Admin\Http\Middleware\Authentication;
use AbterPhp\Admin\Http\Middleware\Authorization;
use AbterPhp\Admin\Http\Middleware\LastGridPage;
use AbterPhp\Files\Constant\Route as RouteConstant;
use AbterPhp\Framework\Authorization\Constant\Role;
use AbterPhp\Framework\Routes\IRouteConfigurator;
use Opulence\Routing\Router;

/**
 * @phan-file-suppress PhanUnreferencedUseNormal
 * @phan-file-suppress PhanInvalidFQSENInCallable
 */
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
        $this->setAdminRoutes($router);
        $this->setApiRoutes($router);
    }

    /**
     * @param Router $router
     */
    private function setAdminRoutes(Router $router)
    {
        $config = $this->config;

        $router->group(
            [
                'path'       => $config->getAdminBasePath(),
                'middleware' => [
                    Authentication::class,
                ],
            ],
            function (Router $router) {
                $entities = [
                    'file-categories' => 'FileCategory',
                    'file-downloads'  => 'FileDownload',
                    'files'           => 'File',
                ];

                foreach ($entities as $route => $controllerName) {
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Grid\File::show() */
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Grid\FileCategory::show() */
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Grid\FileDownload::show() */
                    $router->get(
                        "/${route}",
                        "Admin\Grid\\${controllerName}@show",
                        [
                            RouteConstant::OPTION_NAME       => "${route}-list",
                            RouteConstant::OPTION_MIDDLEWARE => [
                                Authorization::withParameters(
                                    [
                                        Authorization::RESOURCE => $route,
                                        Authorization::ROLE     => Role::READ,
                                    ]
                                ),
                                LastGridPage::class,
                            ],
                        ]
                    );
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Form\File::new() */
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Form\FileCategory::new() */
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Form\FileDownload::new() */
                    $router->get(
                        "/${route}/new",
                        "Admin\Form\\${controllerName}@new",
                        [
                            RouteConstant::OPTION_NAME       => "${route}-new",
                            RouteConstant::OPTION_MIDDLEWARE => [
                                Authorization::withParameters(
                                    [
                                        Authorization::RESOURCE => $route,
                                        Authorization::ROLE     => Role::WRITE,
                                    ]
                                ),
                            ],
                        ]
                    );
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Execute\File::create() */
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Execute\FileCategory::create() */
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Execute\FileDownload::create() */
                    $router->post(
                        "/${route}/new",
                        "Admin\Execute\\${controllerName}@create",
                        [
                            RouteConstant::OPTION_NAME       => "${route}-create",
                            RouteConstant::OPTION_MIDDLEWARE => [
                                Authorization::withParameters(
                                    [
                                        Authorization::RESOURCE => $route,
                                        Authorization::ROLE     => Role::WRITE,
                                    ]
                                ),
                            ],
                        ]
                    );
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Form\File::edit() */
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Form\FileCategory::edit() */
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Form\FileDownload::edit() */
                    $router->get(
                        "/${route}/:entityId/edit",
                        "Admin\Form\\${controllerName}@edit",
                        [
                            RouteConstant::OPTION_NAME       => "${route}-edit",
                            RouteConstant::OPTION_MIDDLEWARE => [
                                Authorization::withParameters(
                                    [
                                        Authorization::RESOURCE => $route,
                                        Authorization::ROLE     => Role::WRITE,
                                    ]
                                ),
                            ],
                        ]
                    );
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Execute\File::update() */
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Execute\FileCategory::update() */
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Execute\FileDownload::update() */
                    $router->put(
                        "/${route}/:entityId/edit",
                        "Admin\Execute\\${controllerName}@update",
                        [
                            RouteConstant::OPTION_NAME       => "${route}-update",
                            RouteConstant::OPTION_MIDDLEWARE => [
                                Authorization::withParameters(
                                    [
                                        Authorization::RESOURCE => $route,
                                        Authorization::ROLE     => Role::WRITE,
                                    ]
                                ),
                            ],
                        ]
                    );
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Execute\File::delete() */
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Execute\FileCategory::delete() */
                    /** @see \AbterPhp\Files\Http\Controllers\Admin\Execute\FileDownload::delete() */
                    $router->get(
                        "/${route}/:entityId/delete",
                        "Admin\Execute\\${controllerName}@delete",
                        [
                            RouteConstant::OPTION_NAME       => "${route}-delete",
                            RouteConstant::OPTION_MIDDLEWARE => [
                                Authorization::withParameters(
                                    [
                                        Authorization::RESOURCE => $route,
                                        Authorization::ROLE     => Role::WRITE,
                                    ]
                                ),
                            ],
                        ]
                    );
                }
            }
        );
    }

    /**
     * @param Router $router
     */
    private function setApiRoutes(Router $router)
    {
        $config = $this->config;

        $router->group(
            [
                'path' => $config->getApiBasePath(),
                'middleware' => [
                    Api::class,
                ],
            ],
            function (Router $router) {
                $entities = [
                    'file-categories' => 'FileCategory',
                    'file-downloads'  => 'FileDownload',
                    'files'          => 'File',
                ];

                foreach ($entities as $route => $controllerName) {
                    /** @see \AbterPhp\Files\Http\Controllers\Api\FileCategory::get() */
                    /** @see \AbterPhp\Files\Http\Controllers\Api\File::get() */
                    /** @see \AbterPhp\Files\Http\Controllers\Api\FileDownload::get() */
                    $router->get(
                        "/${route}/:entityId",
                        "Api\\${controllerName}@get"
                    );

                    /** @see \AbterPhp\Files\Http\Controllers\Api\FileCategory::list() */
                    /** @see \AbterPhp\Files\Http\Controllers\Api\File::list() */
                    /** @see \AbterPhp\Files\Http\Controllers\Api\FileDownload::list() */
                    $router->get(
                        "/${route}",
                        "Api\\${controllerName}@list"
                    );

                    /** @see \AbterPhp\Files\Http\Controllers\Api\FileCategory::create() */
                    /** @see \AbterPhp\Files\Http\Controllers\Api\File::create() */
                    /** @see \AbterPhp\Files\Http\Controllers\Api\FileDownload::create() */
                    $router->post(
                        "/${route}",
                        "Api\\${controllerName}@create"
                    );

                    /** @see \AbterPhp\Files\Http\Controllers\Api\FileCategory::update() */
                    /** @see \AbterPhp\Files\Http\Controllers\Api\File::update() */
                    /** @see \AbterPhp\Files\Http\Controllers\Api\FileDownload::update() */
                    $router->put(
                        "/${route}/:entityId",
                        "Api\\${controllerName}@update"
                    );

                    /** @see \AbterPhp\Files\Http\Controllers\Api\FileCategory::delete() */
                    /** @see \AbterPhp\Files\Http\Controllers\Api\File::delete() */
                    /** @see \AbterPhp\Files\Http\Controllers\Api\FileDownload::delete() */
                    $router->delete(
                        "/${route}/:entityId",
                        "Api\\${controllerName}@delete"
                    );
                }
            }
        );
    }

    /**
     * @param Router $router
     */
    public function setFallbackRoutes(Router $router)
    {
    }
}
