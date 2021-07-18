<?php

use AbterPhp\Framework\Constant\Event;
use AbterPhp\Framework\Constant\Module;
use AbterPhp\Framework\Constant\Priorities;
use AbterPhp\Website\Bootstrappers;
use AbterPhp\Website\Constant\Event as WebsiteEvent;
use AbterPhp\Website\Events;
use AbterPhp\Website\Routes;

return [
    Module::IDENTIFIER          => 'AbterPhp\Website',
    Module::DEPENDENCIES        => ['AbterPhp\Admin'],
    Module::ENABLED             => true,
    Module::BOOTSTRAPPERS       => [
        Bootstrappers\Orm\OrmBootstrapper::class,
        Bootstrappers\Validation\ValidatorBootstrapper::class,
    ],
    Module::CLI_BOOTSTRAPPERS   => [
        Bootstrappers\Database\MigrationsBootstrapper::class,
    ],
    Module::HTTP_BOOTSTRAPPERS  => [
        Bootstrappers\Http\Controllers\Website\IndexBootstrapper::class,
        Bootstrappers\Http\Views\BuildersBootstrapper::class,
        Bootstrappers\Template\Loader\ContentListBootstrapper::class,
        Bootstrappers\Template\Loader\PageCategoryBootstrapper::class,
    ],
    Module::EVENTS              => [
        Event::AUTH_READY            => [
            /** @see Events\Listeners\AuthInitializer::handle */
            Priorities::NORMAL => [sprintf('%s@handle', Events\Listeners\AuthInitializer::class)],
        ],
        Event::TEMPLATE_ENGINE_READY => [
            /** @see Events\Listeners\TemplateInitializer::handle */
            Priorities::NORMAL => [sprintf('%s@handle', Events\Listeners\TemplateInitializer::class)],
        ],
        Event::NAVIGATION_READY      => [
            /** @see Events\Listeners\NavigationBuilder::handle */
            Priorities::NORMAL => [sprintf('%s@handle', Events\Listeners\NavigationBuilder::class)],
        ],
        Event::ENTITY_POST_CHANGE    => [
            /** @see Events\Listeners\PageInvalidator::handle */
            Priorities::NORMAL => [sprintf('%s@handle', Events\Listeners\PageInvalidator::class)],
        ],
        Event::DASHBOARD_READY       => [
            /** @see Events\Listeners\DashboardBuilder::handle */
            Priorities::NORMAL => [sprintf('%s@handle', Events\Listeners\DashboardBuilder::class)],
        ],
        WebsiteEvent::PAGE_VIEWED    => [
            /** @see Events\Listeners\DraftPageChecker::handle */
            Priorities::NORMAL => [sprintf('%s@handle', Events\Listeners\DraftPageChecker::class)],
        ],
    ],
    Module::ROUTE_CONFIGURATORS => [
        Priorities::NORMAL       => [
            Routes\RouteConfigurator::class,
        ],
        Priorities::BELOW_NORMAL => [
            Routes\WebsiteRouteConfigurator::class,
        ],
    ],
    Module::MIGRATION_PATHS     => [
        Priorities::NORMAL => [
            realpath(__DIR__ . '/Database/Migration'),
        ],
    ],
    Module::RESOURCE_PATH       => realpath(__DIR__ . '/resources'),
    Module::ASSETS_PATHS        => [
        'root'         => realpath(__DIR__ . '/resources/rawassets'),
        'website'      => realpath(__DIR__ . '/resources/rawassets'),
        'admin-assets' => realpath(__DIR__ . '/resources/rawassets'),
    ],
];
