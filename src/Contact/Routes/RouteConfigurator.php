<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Routes;

use AbterPhp\Admin\Config\Routes as RoutesConfig;
use AbterPhp\Admin\Http\Middleware\Api;
use AbterPhp\Admin\Http\Middleware\Authentication;
use AbterPhp\Admin\Http\Middleware\Authorization;
use AbterPhp\Admin\Http\Middleware\LastGridPage;
use AbterPhp\Contact\Constant\Route as RouteConstant;
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
        $this->setWebsiteRoutes($router);
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
                    'contact-forms' => 'ContactForm',
                ];

                foreach ($entities as $route => $controllerName) {
                    /** @see \AbterPhp\Contact\Http\Controllers\Admin\Grid\ContactForm::show() */
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
                    /** @see \AbterPhp\Contact\Http\Controllers\Admin\Form\ContactForm::new() */
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
                    /** @see \AbterPhp\Contact\Http\Controllers\Admin\Execute\ContactForm::create() */
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
                    /** @see \AbterPhp\Contact\Http\Controllers\Admin\Form\ContactForm::edit() */
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
                    /** @see \AbterPhp\Contact\Http\Controllers\Admin\Execute\ContactForm::update() */
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
                    /** @see \AbterPhp\Contact\Http\Controllers\Admin\Execute\ContactForm::delete() */
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
                'path'       => $config->getApiBasePath(),
                'middleware' => [
                    Api::class,
                ],
            ],
            function (Router $router) {
                $entities = [
                    'contact-forms' => 'Form',
                ];

                foreach ($entities as $route => $controllerName) {
                    /** @see \AbterPhp\Contact\Http\Controllers\Api\Form::get() */
                    $router->get(
                        "/${route}/:entityId",
                        "Api\\${controllerName}@get"
                    );

                    /** @see \AbterPhp\Contact\Http\Controllers\Api\Form::list() */
                    $router->get(
                        "/${route}",
                        "Api\\${controllerName}@list"
                    );

                    /** @see \AbterPhp\Contact\Http\Controllers\Api\Form::create() */
                    $router->post(
                        "/${route}",
                        "Api\\${controllerName}@create"
                    );

                    /** @see \AbterPhp\Contact\Http\Controllers\Api\Form::update() */
                    $router->put(
                        "/${route}/:entityId",
                        "Api\\${controllerName}@update"
                    );

                    /** @see \AbterPhp\Contact\Http\Controllers\Api\Form::delete() */
                    $router->delete(
                        "/${route}/:entityId",
                        "Api\\${controllerName}@delete"
                    );
                }

                /** @see \AbterPhp\Contact\Http\Controllers\Api\Message::create() */
                $router->post(
                    "/contactforms/:entityId/messages",
                    "Api\\Message@create"
                );
            }
        );
    }

    /**
     * @param Router $router
     */
    private function setWebsiteRoutes(Router $router)
    {
        $router->group(
            ['controllerNamespace' => 'AbterPhp\Contact\Http\Controllers'],
            function (Router $router) {
                /** @see \AbterPhp\Contact\Http\Controllers\Website\Contact::submit() */
                $router->post(
                    '/contact/:formIdentifier',
                    'Website\Contact@submit',
                    [RouteConstant::OPTION_NAME => RouteConstant::CONTACT]
                );
            }
        );
    }
}
