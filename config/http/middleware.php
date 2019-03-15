<?php

declare(strict_types=1);

use Opulence\Framework\Http\Middleware\CheckMaintenanceMode;

/**
 * ----------------------------------------------------------
 * Define the list of middleware to be run on all routes
 * ----------------------------------------------------------
 */
return [
    CheckMaintenanceMode::class,
];
