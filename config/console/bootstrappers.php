<?php

declare(strict_types=1);

use Opulence\Framework\Composer\Bootstrappers\ComposerBootstrapper;
use AbterPhp\Framework\Bootstrappers\Console\CommandsBootstrapper;
use Opulence\Framework\Console\Bootstrappers\RequestBootstrapper;

/**
 * ----------------------------------------------------------
 * Define bootstrapper classes for a console application
 *
 * Note: abter.php files are scanned for more bootstrappers
 * ----------------------------------------------------------
 */
return [
    CommandsBootstrapper::class,
    RequestBootstrapper::class,
    ComposerBootstrapper::class,
];
