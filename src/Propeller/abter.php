<?php

namespace AbterPhp\Propeller;

use AbterPhp\Framework\Constant\Event;
use AbterPhp\Framework\Constant\Module;
use AbterPhp\Admin\Constant\Event as AdminEvent;

return [
    Module::IDENTIFIER   => 'AbterPhp\Propeller',
    Module::DEPENDENCIES => ['AbterPhp\Admin'],
    Module::HTTP_BOOTSTRAPPERS => [
        Bootstrappers\Events\Listeners::class,
    ],
    Module::EVENTS             => [
        Event::FORM_READY => [
            /** @see \AbterPhp\Propeller\Events\Listeners\FormDecorator::handle() */
            sprintf('%s@handle', Events\Listeners\FormDecorator::class),
        ],
        Event::GRID_READY => [
            /** @see \AbterPhp\Propeller\Events\Listeners\GridDecorator::handle() */
            sprintf('%s@handle', Events\Listeners\GridDecorator::class),
        ],
        AdminEvent::ADMIN_READY  => [
            /** @see \AbterPhp\Propeller\Events\Listeners\AdminDecorator::handle() */
            sprintf('%s@handle', Events\Listeners\AdminDecorator::class),
        ],
        AdminEvent::LOGIN_READY  => [
            /** @see \AbterPhp\Propeller\Events\Listeners\LoginDecorator::handle() */
            sprintf('%s@handle', Events\Listeners\LoginDecorator::class),
        ],
    ],
];
