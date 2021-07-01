<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Routes;

use AbterPhp\Admin\Config\Routes as RoutesConfig;
use AbterPhp\Admin\Constant\Route as RouteConstant;
use AbterPhp\Admin\Http\Middleware\Api;
use AbterPhp\Admin\Http\Middleware\Authentication;
use AbterPhp\Admin\Http\Middleware\Authorization;
use AbterPhp\Admin\Http\Middleware\LastGridPage;
use AbterPhp\Framework\Authorization\Constant\Role;
use AbterPhp\Framework\Routes\IRouteConfigurator;
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
        $this->setAdminRoutes($router);
        $this->setApiRoutes($router);
        $this->setLoginRoutes($router);
    }

    /**
     * @param Router $router
     */
    private function setAdminRoutes(Router $router)
    {
        $config = $this->config;

        $router->group(
            ['controllerNamespace' => 'AbterPhp\Admin\\Http\\Controllers'],
            function (Router $router) use ($config) {
                $router->group(
                    [
                        'path'       => $config->getAdminBasePath(),
                        'middleware' => [
                            Authentication::class,
                        ],
                    ],
                    function (Router $router) {
                        $entities = [
                            'user-groups' => 'UserGroup',
                            'users'       => 'User',
                            'api-clients' => 'ApiClient',
                        ];

                        foreach ($entities as $route => $controllerName) {
                            /** @see \AbterPhp\Admin\Http\Controllers\Admin\Grid\User::show() */
                            /** @see \AbterPhp\Admin\Http\Controllers\Admin\Grid\UserGroup::show() */
                            /** @see \AbterPhp\Admin\Http\Controllers\Admin\Grid\ApiClient::show() */
                            $router->get(
                                "/${route}",
                                "\AbterPhp\Admin\Http\Controllers\Admin\Grid\\${controllerName}@show",
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

                            /** @see \AbterPhp\Admin\Http\Controllers\Admin\Form\User::new() */
                            /** @see \AbterPhp\Admin\Http\Controllers\Admin\Form\UserGroup::new() */
                            /** @see \AbterPhp\Admin\Http\Controllers\Admin\Form\ApiClient::new() */
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

                            /** @see \AbterPhp\Admin\Http\Controllers\Admin\Execute\User::create() */
                            /** @see \AbterPhp\Admin\Http\Controllers\Admin\Execute\UserGroup::create() */
                            /** @see \AbterPhp\Admin\Http\Controllers\Admin\Execute\ApiClient::create() */
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

                            /** @see \AbterPhp\Admin\Http\Controllers\Admin\Form\User::edit() */
                            /** @see \AbterPhp\Admin\Http\Controllers\Admin\Form\UserGroup::edit() */
                            /** @see \AbterPhp\Admin\Http\Controllers\Admin\Form\ApiClient::edit() */
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

                            /** @see \AbterPhp\Admin\Http\Controllers\Admin\Execute\User::update() */
                            /** @see \AbterPhp\Admin\Http\Controllers\Admin\Execute\UserGroup::update() */
                            /** @see \AbterPhp\Admin\Http\Controllers\Admin\Execute\ApiClient::update() */
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

                            /** @see \AbterPhp\Admin\Http\Controllers\Admin\Execute\User::delete() */
                            /** @see \AbterPhp\Admin\Http\Controllers\Admin\Execute\UserGroup::delete() */
                            /** @see \AbterPhp\Admin\Http\Controllers\Admin\Execute\ApiClient::delete() */
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

                        /** @see \AbterPhp\Admin\Http\Controllers\Admin\Form\Profile::profile() */
                        $router->get(
                            RoutesConfig::PROFILE_PATH,
                            'Admin\Form\Profile@profile',
                            [
                                RouteConstant::OPTION_NAME => RouteConstant::PROFILE_EDIT,
                            ]
                        );

                        /** @see \AbterPhp\Admin\Http\Controllers\Admin\Execute\Profile::execute() */
                        $router->put(
                            RoutesConfig::PROFILE_PATH,
                            'Admin\Execute\Profile@profile',
                            [
                                RouteConstant::OPTION_NAME => RouteConstant::PROFILE_UPDATE,
                            ]
                        );

                        /** @see \AbterPhp\Admin\Http\Controllers\Admin\Dashboard::showDashboard() */
                        $router->get(
                            RoutesConfig::DASHBOARD_PATH,
                            'Admin\Dashboard@showDashboard',
                            [
                                RouteConstant::OPTION_NAME => RouteConstant::DASHBOARD_VIEW,
                            ]
                        );
                    }
                );
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
            ['controllerNamespace' => 'AbterPhp\Admin\\Http\\Controllers'],
            function (Router $router) use ($config) {
                $router->group(
                    [
                        'path' => $config->getApiBasePath(),
                    ],
                    function (Router $router) {
                        /** @see \AbterPhp\Admin\Http\Controllers\Api\AccessToken::create() */
                        $router->post(
                            '/access-tokens',
                            'Api\AccessToken@create',
                            [
                                RouteConstant::OPTION_NAME => RouteConstant::ACCESS_TOKENS_BASE,
                            ]
                        );
                        /** @see \AbterPhp\Admin\Http\Controllers\Api\Editor::fileUpload() */
                        $router->any(
                            '/editor-file-upload',
                            'Api\Editor@fileUpload',
                            []
                        );
                    }
                );
                $router->group(
                    [
                        'path'       => $config->getApiBasePath(),
                        'middleware' => [
                            Api::class,
                        ],
                    ],
                    function (Router $router) {
                        $entities = [
                            'user-groups'    => 'UserGroup',
                            'user-languages' => 'UserLanguage',
                            'users'          => 'User',
                            'apic-lients'    => 'ApiClient',
                        ];

                        foreach ($entities as $route => $controllerName) {
                            /** @see \AbterPhp\Admin\Http\Controllers\Api\UserLanguage::get() */
                            /** @see \AbterPhp\Admin\Http\Controllers\Api\UserGroup::get() */
                            /** @see \AbterPhp\Admin\Http\Controllers\Api\User::get() */
                            /** @see \AbterPhp\Admin\Http\Controllers\Api\ApiClient::get() */
                            $router->get(
                                "/${route}/:entityId",
                                "Api\\${controllerName}@get"
                            );

                            /** @see \AbterPhp\Admin\Http\Controllers\Api\UserLanguage::list() */
                            /** @see \AbterPhp\Admin\Http\Controllers\Api\UserGroup::list() */
                            /** @see \AbterPhp\Admin\Http\Controllers\Api\User::list() */
                            /** @see \AbterPhp\Admin\Http\Controllers\Api\ApiClient::list() */
                            $router->get(
                                "/${route}",
                                "Api\\${controllerName}@list"
                            );

                            /** @see \AbterPhp\Admin\Http\Controllers\Api\UserLanguage::create() */
                            /** @see \AbterPhp\Admin\Http\Controllers\Api\UserGroup::create() */
                            /** @see \AbterPhp\Admin\Http\Controllers\Api\User::create() */
                            /** @see \AbterPhp\Admin\Http\Controllers\Api\ApiClient::create() */
                            $router->post(
                                "/${route}",
                                "Api\\${controllerName}@create"
                            );

                            /** @see \AbterPhp\Admin\Http\Controllers\Api\UserLanguage::update() */
                            /** @see \AbterPhp\Admin\Http\Controllers\Api\UserGroup::update() */
                            /** @see \AbterPhp\Admin\Http\Controllers\Api\User::update() */
                            /** @see \AbterPhp\Admin\Http\Controllers\Api\ApiClient::update() */
                            $router->put(
                                "/${route}/:entityId",
                                "Api\\${controllerName}@update"
                            );

                            /** @see \AbterPhp\Admin\Http\Controllers\Api\UserLanguage::delete() */
                            /** @see \AbterPhp\Admin\Http\Controllers\Api\UserGroup::delete() */
                            /** @see \AbterPhp\Admin\Http\Controllers\Api\User::delete() */
                            /** @see \AbterPhp\Admin\Http\Controllers\Api\ApiClient::delete() */
                            $router->delete(
                                "/${route}/:entityId",
                                "Api\\${controllerName}@delete"
                            );
                        }
                    }
                );
            }
        );
    }

    /**
     * @param Router $router
     */
    private function setLoginRoutes(Router $router)
    {
        $config = $this->config;

        $router->group(
            ['controllerNamespace' => 'AbterPhp\Admin\\Http\\Controllers'],
            function (Router $router) use ($config) {
                /** @see \AbterPhp\Admin\Http\Controllers\Admin\Form\Login::display() */
                $router->get(
                    $config->getLoginPath(),
                    'Admin\Form\Login@display',
                    [RouteConstant::OPTION_NAME => RouteConstant::LOGIN_NEW]
                );

                /** @see \AbterPhp\Admin\Http\Controllers\Admin\Execute\Login::execute() */
                $router->post(
                    $config->getLoginPath(),
                    'Admin\Execute\Login@execute',
                    [RouteConstant::OPTION_NAME => RouteConstant::LOGIN_EXECUTE]
                );

                /** @see \AbterPhp\Admin\Http\Controllers\Admin\Execute\Logout::execute() */
                $router->get(
                    $config->getLoginPath(),
                    'Admin\Execute\Logout@execute',
                    [RouteConstant::OPTION_NAME => RouteConstant::LOGOUT_EXECUTE]
                );
            }
        );
    }
}
