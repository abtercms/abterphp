<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Module;

use AbterPhp\Framework\Constant\Module;
use Opulence\Cache\ICacheBridge;
use Opulence\Console\Commands\Command;
use Opulence\Ioc\Bootstrappers\Bootstrapper;

class Manager
{
    const CACHE_KEY_HTTP_BOOTSTRAPPERS = 'AbterPhp:HttpBootstrappers';
    const CACHE_KEY_CLI_BOOTSTRAPPERS  = 'AbterPhp:CliBootstrappers';
    const CACHE_KEY_COMMANDS           = 'AbterPhp:Commands';
    const CACHE_KEY_ROUTE_PATHS        = 'AbterPhp:RoutePaths';
    const CACHE_KEY_EVENTS             = 'AbterPhp:Events';
    const CACHE_KEY_MIDDLEWARE         = 'AbterPhp:Middleware';
    const CACHE_KEY_MIGRATION_PATHS    = 'AbterPhp:MigrationPaths';

    /** @var Loader */
    protected $loader;

    /** @var ICacheBridge|null */
    protected $cacheBridge;

    /** @var array|null */
    protected $modules;

    /**
     * Manager constructor.
     *
     * @param Loader            $sourceRoots
     * @param ICacheBridge|null $cacheBridge
     */
    public function __construct(Loader $loader, ?ICacheBridge $cacheBridge = null)
    {
        $this->loader      = $loader;
        $this->cacheBridge = $cacheBridge;
    }

    /**
     * @return Bootstrapper[]
     */
    public function getHttpBootstrappers(): array
    {
        try {
            if ($this->cacheBridge && $this->cacheBridge->has(static::CACHE_KEY_HTTP_BOOTSTRAPPERS)) {
                return $this->cacheBridge->get(static::CACHE_KEY_HTTP_BOOTSTRAPPERS);
            }
        } catch (\Exception $e) {
        }

        if (null === $this->modules) {
            $this->init();
        }

        $bootstrappers = [];
        foreach ($this->modules as $module) {
            if (isset($module[Module::BOOTSTRAPPERS])) {
                $bootstrappers = array_merge($bootstrappers, $module[Module::BOOTSTRAPPERS]);
            }
            if (isset($module[Module::HTTP_BOOTSTRAPPERS])) {
                $bootstrappers = array_merge($bootstrappers, $module[Module::HTTP_BOOTSTRAPPERS]);
            }
        }

        try {
            if ($this->cacheBridge) {
                $this->cacheBridge->set(static::CACHE_KEY_HTTP_BOOTSTRAPPERS, $bootstrappers, PHP_INT_MAX);
            }
        } catch (\Exception $e) {
        }

        return $bootstrappers;
    }

    /**
     * @return Bootstrapper[]
     */
    public function getCliBootstrappers(): array
    {
        try {
            if ($this->cacheBridge && $this->cacheBridge->has(static::CACHE_KEY_CLI_BOOTSTRAPPERS)) {
                return $this->cacheBridge->get(static::CACHE_KEY_CLI_BOOTSTRAPPERS);
            }
        } catch (\Exception $e) {
        }

        if (null === $this->modules) {
            $this->init();
        }

        $bootstrappers = [];
        foreach ($this->modules as $module) {
            if (isset($module[Module::BOOTSTRAPPERS])) {
                $bootstrappers = array_merge($bootstrappers, $module[Module::BOOTSTRAPPERS]);
            }
            if (isset($module[Module::CLI_BOOTSTRAPPERS])) {
                $bootstrappers = array_merge($bootstrappers, $module[Module::CLI_BOOTSTRAPPERS]);
            }
        }

        try {
            if ($this->cacheBridge) {
                $this->cacheBridge->set(static::CACHE_KEY_CLI_BOOTSTRAPPERS, $bootstrappers, PHP_INT_MAX);
            }
        } catch (\Exception $e) {
        }

        return $bootstrappers;
    }

    /**
     * @return Command[]
     */
    public function getCommands(): array
    {
        try {
            if ($this->cacheBridge && $this->cacheBridge->has(static::CACHE_KEY_COMMANDS)) {
                return $this->cacheBridge->get(static::CACHE_KEY_COMMANDS);
            }
        } catch (\Exception $e) {
        }

        if (null === $this->modules) {
            $this->init();
        }

        $commands = [];
        foreach ($this->modules as $module) {
            if (isset($module[Module::COMMANDS])) {
                $commands = array_merge($commands, $module[Module::COMMANDS]);
            }
        }

        try {
            if ($this->cacheBridge) {
                $this->cacheBridge->set(static::CACHE_KEY_COMMANDS, $commands, PHP_INT_MAX);
            }
        } catch (\Exception $e) {
        }

        return $commands;
    }

    /**
     * @return Command[]
     */
    public function getEvents(): array
    {
        try {
            if ($this->cacheBridge && $this->cacheBridge->has(static::CACHE_KEY_EVENTS)) {
                return $this->cacheBridge->get(static::CACHE_KEY_EVENTS);
            }
        } catch (\Exception $e) {
        }

        if (null === $this->modules) {
            $this->init();
        }

        $allEvents = [];
        foreach ($this->modules as $module) {
            if (!isset($module[Module::EVENTS])) {
                continue;
            }
            foreach ($module[Module::EVENTS] as $eventType => $events) {
                if (!isset($allEvents[$eventType])) {
                    $allEvents[$eventType] = [];
                }
                $allEvents[$eventType] = array_merge($allEvents[$eventType], $events);
            }
        }

        try {
            if ($this->cacheBridge) {
                $this->cacheBridge->set(static::CACHE_KEY_EVENTS, $allEvents, PHP_INT_MAX);
            }
        } catch (\Exception $e) {
        }

        return $allEvents;
    }

    /**
     * @return string[][]
     */
    public function getMiddleware(): array
    {
        try {
            if ($this->cacheBridge && $this->cacheBridge->has(static::CACHE_KEY_MIDDLEWARE)) {
                return $this->cacheBridge->get(static::CACHE_KEY_MIDDLEWARE);
            }
        } catch (\Exception $e) {
        }

        if (null === $this->modules) {
            $this->init();
        }

        $middleware = [];
        foreach ($this->modules as $module) {
            if (!isset($module[Module::MIDDLEWARE])) {
                continue;
            }
            foreach ($module[Module::MIDDLEWARE] as $priority => $prioMiddleware) {
                if (!isset($middleware[$priority])) {
                    $middleware[$priority] = [];
                }
                $middleware[$priority] = array_merge($middleware[$priority], $prioMiddleware);
            }
        }

        try {
            if ($this->cacheBridge) {
                $this->cacheBridge->set(static::CACHE_KEY_MIDDLEWARE, $middleware, PHP_INT_MAX);
            }
        } catch (\Exception $e) {
        }

        return $middleware;
    }

    /**
     * @return string[]
     */
    public function getRoutePaths(): array
    {
        try {
            if ($this->cacheBridge && $this->cacheBridge->has(static::CACHE_KEY_ROUTE_PATHS)) {
                return $this->cacheBridge->get(static::CACHE_KEY_ROUTE_PATHS);
            }
        } catch (\Exception $e) {
        }

        if (null === $this->modules) {
            $this->init();
        }

        $paths = [];
        foreach ($this->modules as $module) {
            if (!isset($module[Module::ROUTE_PATHS])) {
                continue;
            }
            foreach ($module[Module::ROUTE_PATHS] as $priority => $path) {
                $paths[$priority][] = $path;
            }
        }

        ksort($paths);

        $flatPaths = [];
        foreach ($paths as $priorityPaths) {
            $flatPaths = array_merge($flatPaths, $priorityPaths);
        }

        try {
            if ($this->cacheBridge) {
                $this->cacheBridge->set(static::CACHE_KEY_ROUTE_PATHS, $flatPaths, PHP_INT_MAX);
            }
        } catch (\Exception $e) {
        }

        return $flatPaths;
    }

    /**
     * @return string[]
     */
    public function getMigrationPaths(): array
    {
        try {
            if ($this->cacheBridge && $this->cacheBridge->has(static::CACHE_KEY_MIGRATION_PATHS)) {
                return $this->cacheBridge->get(static::CACHE_KEY_MIGRATION_PATHS);
            }
        } catch (\Exception $e) {
        }

        if (null === $this->modules) {
            $this->init();
        }

        $paths = [];
        foreach ($this->modules as $module) {
            if (!isset($module[Module::MIGRATION_PATHS])) {
                continue;
            }
            foreach ($module[Module::MIGRATION_PATHS] as $priority => $priorityPaths) {
                if (!isset($paths[$priority])) {
                    $paths[$priority] = [];
                }
                $paths[$priority] = array_merge($paths[$priority], $priorityPaths);
            }
        }

        ksort($paths);

        $flatPaths = [];
        foreach ($paths as $priorityPaths) {
            $flatPaths = array_merge($flatPaths, $priorityPaths);
        }

        try {
            if ($this->cacheBridge) {
                $this->cacheBridge->set(static::CACHE_KEY_ROUTE_PATHS, $flatPaths, PHP_INT_MAX);
            }
        } catch (\Exception $e) {
        }

        return $flatPaths;
    }

    /**
     * @return array
     */
    protected function init(): Manager
    {
        if ($this->modules) {
            return $this;
        }

        $this->modules = $this->loader->loadModules();

        return $this;
    }
}
