<?php

declare(strict_types=1);

use AbterPhp\Framework\Constant\Env;
use Opulence\Cache\FileBridge;
use Opulence\Environments\Environment;
use Opulence\Framework\Configuration\Config;

/**
 * ----------------------------------------------------------
 * Define the auth config
 * ----------------------------------------------------------
 */
return [
    /**
     * ----------------------------------------------------------
     * Cache-backed auth settings
     * ----------------------------------------------------------
     *
     * "cache.bridge" => The name of the cache bridge class your authorization use
     * "cache.clientName" => The name of the client to use in your cache bridge
     * "cache.keyPrefix" => The prefix to use on all cache keys to avoid naming collisions
     */
    'cache.bridge'     => Environment::getVar(
        Env::AUTH_CACHE_BRIDGE,
        Environment::getVar(Env::SESSION_CACHE_BRIDGE, FileBridge::class)
    ),
    'cache.clientName' => 'auth',
    'cache.keyPrefix'  => 'authorization:',

    /**
     * ----------------------------------------------------------
     * File-backed cache settings
     * ----------------------------------------------------------
     *
     * "file.path" => The path of the session file
     */
    'file.path'        => Config::get('paths', 'tmp.framework.http') . '/auth',
];
