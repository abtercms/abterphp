<?php

use AbterPhp\Bootstrap4Website\Bootstrappers;
use AbterPhp\Bootstrap4Website\Events;
use AbterPhp\Framework\Constant\Event;
use AbterPhp\Framework\Constant\Module;
use AbterPhp\Framework\Constant\Priorities;
use AbterPhp\Website\Constant\Event as WebsiteEvent;

return [
    Module::IDENTIFIER         => 'AbterPhp\Bootstrap4Website',
    Module::DEPENDENCIES       => ['AbterPhp\Website'],
    Module::ENABLED            => true,
    Module::HTTP_BOOTSTRAPPERS => [
        Bootstrappers\Events\Listeners::class,
    ],
    Module::EVENTS             => [
        WebsiteEvent::WEBSITE_READY => [
            /** @see Events\Listeners\WebsiteDecorator::handle */
            Priorities::NORMAL => [sprintf('%s@handle', Events\Listeners\WebsiteDecorator::class)],
        ],
        Event::FORM_READY           => [
            /** @see Events\Listeners\FormDecorator::handle */
            Priorities::EXTREME_LOW => [sprintf('%s@handle', Events\Listeners\FormDecorator::class)],
        ],
    ],
    Module::RESOURCE_PATH      => realpath(__DIR__ . '/resources'),
];
