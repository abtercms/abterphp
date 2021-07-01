<?php

declare(strict_types=1);

namespace AbterPhp\Website\Routes;

use AbterPhp\Admin\Config\Routes as RoutesConfig;
use AbterPhp\Admin\Http\Middleware\Api;
use AbterPhp\Admin\Http\Middleware\Authentication;
use AbterPhp\Admin\Http\Middleware\Authorization;
use AbterPhp\Admin\Http\Middleware\LastGridPage;
use AbterPhp\Framework\Authorization\Constant\Role;
use AbterPhp\Framework\Routes\IRouteConfigurator;
use AbterPhp\Website\Constant\Route as RouteConstant;
use Opulence\Routing\Router;

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
    protected function setAdminRoutes(Router $router)
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
                    'blocks'          => 'Block',
                    'block-layouts'   => 'BlockLayout',
                    'lists'           => 'ContentList',
                    'pages'           => 'Page',
                    'page-layouts'    => 'PageLayout',
                    'page-categories' => 'PageCategory',
                ];

                foreach ($entities as $route => $controllerName) {
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Grid\Block::show() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Grid\BlockLayout::show() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Grid\ContentList::show() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Grid\Page::show() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Grid\PageLayout::show() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Grid\PageCategory::show() */
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

                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\Block::new() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\BlockLayout::new() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\ContentList::new() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\Page::new() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\PageLayout::new() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\PageCategory::new() */
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

                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\Block::create() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\BlockLayout::create() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\ContentList::create() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\Page::create() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\PageLayout::create() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\PageCategory::create() */
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

                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\Block::edit() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\BlockLayout::edit() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\ContentList::edit() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\Page::edit() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\PageLayout::edit() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\PageCategory::edit() */
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

                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Execute\Block::update() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Execute\BlockLayout::update() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Execute\ContentList::update() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Execute\Page::update() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Execute\PageLayout::update() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Execute\PageCategory::update() */
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

                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Execute\Block::delete() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Execute\BlockLayout::delete() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Execute\ContentList::delete() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Execute\Page::delete() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Execute\PageLayout::delete() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Execute\PageCategory::delete() */
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
    protected function setApiRoutes(Router $router)
    {
        $config = $this->config;

        $router->group(
            [
                'path'       => $config->getApiBasePath(),
                'middleware' => [
                    Api::class,
                ],
            ],
            function (Router $router) {
                $entities = [
                    'pages'           => 'Page',
                    'page-layouts'    => 'PageLayout',
                    'page-categories' => 'PageCategory',
                    'blocks'          => 'Block',
                    'block-layouts'   => 'BlockLayout',
                ];

                foreach ($entities as $route => $controllerName) {
                    /** @see \AbterPhp\Website\Http\Controllers\Api\Page::get() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\PageLayout::get() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\PageCategory::get() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\Block::get() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\BlockLayout::get() */
                    $router->get(
                        "/${route}/:entityId",
                        "Api\\${controllerName}@get"
                    );

                    /** @see \AbterPhp\Website\Http\Controllers\Api\Page::list() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\PageLayout::list() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\PageCategory::list() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\Block::list() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\BlockLayout::list() */
                    $router->get(
                        "/${route}",
                        "Api\\${controllerName}@list"
                    );

                    /** @see \AbterPhp\Website\Http\Controllers\Api\Page::create() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\PageLayout::create() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\PageCategory::create() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\Block::create() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\BlockLayout::create() */
                    $router->post(
                        "/${route}",
                        "Api\\${controllerName}@create"
                    );

                    /** @see \AbterPhp\Website\Http\Controllers\Api\Page::update() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\PageLayout::update() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\PageCategory::update() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\Block::update() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\BlockLayout::update() */
                    $router->put(
                        "/${route}/:entityId",
                        "Api\\${controllerName}@update"
                    );

                    /** @see \AbterPhp\Website\Http\Controllers\Api\Page::delete() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\PageLayout::delete() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\PageCategory::delete() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\Block::delete() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\BlockLayout::delete() */
                    $router->delete(
                        "/${route}/:entityId",
                        "Api\\${controllerName}@delete"
                    );
                }
            }
        );
    }
}
