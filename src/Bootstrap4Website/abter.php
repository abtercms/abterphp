<?php

namespace AbterPhp\Bootstrap4Website;

use AbterPhp\Framework\Constant\Module;
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
            /** @see \AbterPhp\Bootstrap4Website\Events\Listeners\WebsiteDecorator::handle */
            sprintf('%s@handle', Events\Listeners\WebsiteDecorator::class),
        ],
    ],
];
