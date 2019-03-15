<?php

declare(strict_types=1);

use AbterPhp\Framework\Constant\Env;
use Opulence\Cache\FileBridge;
use Opulence\Environments\Environment;
use Opulence\Framework\Configuration\Config;

/**
 * ----------------------------------------------------------
 * Define the template config
 * ----------------------------------------------------------
 */
return [
    /**
     * ----------------------------------------------------------
     * Cache-backed template settings
     * ----------------------------------------------------------
     *
     * "cache.bridge" => The name of the cache bridge class your templates use
     * "cache.clientName" => The name of the client to use in your cache bridge
     * "cache.keyPrefix" => The prefix to use on all cache keys to avoid naming collisions
     */
    'cache.bridge'     => Environment::getVar(
        Env::TEMPLATE_CACHE_BRIDGE,
        Environment::getVar(Env::SESSION_CACHE_BRIDGE, FileBridge::class)
    ),
    'cache.clientName' => 'templates',
    'cache.keyPrefix'  => 'templates:',

    /**
     * ----------------------------------------------------------
     * File-backed session settings
     * ----------------------------------------------------------
     *
     * "file.path" => The path of the session file
     */
    'file.path'        => Config::get('paths', 'tmp.framework.http') . '/templates',
];
