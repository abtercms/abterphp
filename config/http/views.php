<?php

declare(strict_types=1);

use AbterPhp\Framework\Constant\Env;
use Opulence\Environments\Environment;
use Opulence\Views\Caching\FileCache;

/**
 * ----------------------------------------------------------
 * Define the view config
 * ----------------------------------------------------------
 */
return [
    /**
     * ----------------------------------------------------------
     * General settings
     * ----------------------------------------------------------
     *
     * "cache" => The name of the view cache class
     * "cache.lifetime" => Lifetime of the cached views in seconds
     */
    'cache' => Environment::getVar(Env::VIEW_CACHE, FileCache::class),
    'cache.lifetime' => 3600,

    /**
     * ----------------------------------------------------------
     * Garbage collection settings
     * ----------------------------------------------------------
     *
     * "gc.chance" => The chance that garbage collection will be run
     * "gc.divisor" => The divisor to calculate the probability (default is 1 in 100 chance)
     */
    'gc.chance' => 1,
    'gc.divisor' => 100
];
