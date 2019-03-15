<?php

namespace AbterPhp\Bootstrap4;

use AbterPhp\Framework\Constant\Module;
use AbterPhp\Website\Constant\Events as WebsiteEvents;

return [
    Module::IDENTIFIER   => 'AbterPhp\Bootstrap4',
    Module::DEPENDENCIES => ['AbterPhp\Website'],
    Module::HTTP_BOOTSTRAPPERS => [
        Events\Bootstrappers\Listeners::class,
    ],
    Module::EVENTS             => [
        WebsiteEvents::WEBSITE_READY  => [
            /** @see \AbterPhp\Bootstrap4\Events\Listeners\WebsiteDecorator::handle() */
            sprintf('%s@handle', Events\Listeners\WebsiteDecorator::class),
        ],
    ],
];
