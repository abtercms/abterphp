<?php

declare(strict_types=1);

/**
 * ----------------------------------------------------------
 * Define the list of paths needed by this application
 * ----------------------------------------------------------
 */
return [
    /**
     * ----------------------------------------------------------
     * Configs
     * ----------------------------------------------------------
     *
     * "config" => The config directory
     * "config.console" => The console config directory
     * "config.http" => The Http config directory
     */
    'config' => realpath(__DIR__),
    'config.console' => realpath(__DIR__ . '/console'),
    'config.http' => realpath(__DIR__ . '/http'),

    /**
     * ----------------------------------------------------------
     * Database
     * ----------------------------------------------------------
     *
     * "database.migrations" => The directory that holds your migration classes
     */
    'database.migrations' => [],

    /**
     * ----------------------------------------------------------
     * Logs
     * ----------------------------------------------------------
     *
     * "logs" => The logs directory
     */
    'logs' => realpath(__DIR__ . '/../tmp/logs'),

    /**
     * ----------------------------------------------------------
     * Public
     * ----------------------------------------------------------
     *
     * "public" => The public directory
     */
    'public' => realpath(__DIR__ . '/../public'),

    /**
     * ----------------------------------------------------------
     * Resources
     * ----------------------------------------------------------
     *
     * "resources" => The resources directory
     * "resources.lang" => The language resources directory
     * "resources.lang.en" => The English language resources directory
     */
    'resources' => realpath(__DIR__ . '/../resources'),
    'resources.lang' => realpath(__DIR__ . '/../resources/lang'),
    'resources.lang.en' => realpath(__DIR__ . '/../resources/lang/en'),
    'resources.bootstrap4' => realpath(__DIR__ . '/../resources/bootstrap4'),
    'resources.propeller' => realpath(__DIR__ . '/../resources/propeller'),

    /**
     * ----------------------------------------------------------
     * Root
     * ----------------------------------------------------------
     *
     * "root" => The root directory
     */
    'root' => realpath(__DIR__ . '/..'),

    /**
     * ----------------------------------------------------------
     * Routes
     * ----------------------------------------------------------
     *
     * "routes.cache" => The cached routes directory
     */
    'routes.cache' => realpath(__DIR__ . '/../tmp/framework/http/routing'),

    /**
     * ----------------------------------------------------------
     * Source
     * ----------------------------------------------------------
     *
     * "src" => The application source directory
     */
    'src' => realpath(__DIR__ . '/../src'),

    /**
     * ----------------------------------------------------------
     * Tests
     * ----------------------------------------------------------
     *
     * "tests" => The tests directory
     */
    'tests' => realpath(__DIR__ . '/../tests/src'),

    /**
     * ----------------------------------------------------------
     * Temporary
     * ----------------------------------------------------------
     *
     * "tmp" => The temporary directory
     * "tmp.framework.console" => The framework's temporary console directory
     * "tmp.framework.http" => The framework's temporary Http directory
     * "tmp.framework.shared" => The framework's Http and console temporary directory
     */
    'tmp' => realpath(__DIR__ . '/../tmp'),
    'tmp.framework.console' => realpath(__DIR__ . '/../tmp/framework/console'),
    'tmp.framework.http' => realpath(__DIR__ . '/../tmp/framework/http'),
    'tmp.framework.shared' => realpath(__DIR__ . '/../tmp/framework/shared'),

    /**
     * ----------------------------------------------------------
     * Vendor
     * ----------------------------------------------------------
     *
     * "vendor" => The vendor directory
     */
    'vendor' => realpath(__DIR__ . '/../vendor'),

    /**
     * ----------------------------------------------------------
     * Views
     * ----------------------------------------------------------
     *
     * "views.compiled" => The compiled views directory
     * "views.raw" => The raw views directory
     */
    'views.compiled' => realpath(__DIR__ . '/../tmp/framework/http/views'),
    'views.raw' => realpath(__DIR__ . '/../resources/views')
];
