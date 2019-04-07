<?php

declare(strict_types=1);

use AbterPhp\Admin\Http\Middleware\Authentication;
use AbterPhp\Admin\Http\Middleware\Authorization;
use AbterPhp\Admin\Http\Middleware\LastGridPage;
use AbterPhp\Framework\Authorization\Constant\Role;
use AbterPhp\Website\Constant\Routes;
use Opulence\Routing\Router;

/**
 * ----------------------------------------------------------
 * Create all of the routes for the HTTP kernel
 * ----------------------------------------------------------
 *
 * @var Router $router
 */
$router->group(
    ['controllerNamespace' => 'AbterPhp\Website\Http\Controllers'],
    function (Router $router) {

        /** @see \AbterPhp\Framework\Http\Controllers\Website\Index::notFound() */
        $router->any(
            Routes::PATH_404,
            'Website\Index@notFound',
            [
                OPTION_NAME => Routes::ROUTE_404,
                OPTION_VARS => [Routes::VAR_ANYTHING => '.+'],
            ]
        );
    }
);
