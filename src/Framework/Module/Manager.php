<?php

namespace AbterPhp\Framework\Module;

use AbterPhp\Framework\Constant\Module;
use Opulence\Cache\ICacheBridge;
use Opulence\Console\Commands\Command;
use Opulence\Ioc\Bootstrappers\Bootstrapper;

class Manager
{
    const MODULE_FILE_NAME = 'abter.php';

    const CACHE_KEY_HTTP_BOOTSTRAPPERS = 'AbterPhp:HttpBootstrappers';
    const CACHE_KEY_CLI_BOOTSTRAPPERS  = 'AbterPhp:CliBootstrappers';
    const CACHE_KEY_COMMANDS           = 'AbterPhp:Commands';
    const CACHE_KEY_ROUTE_PATHS        = 'AbterPhp:RoutePaths';
    const CACHE_KEY_EVENTS             = 'AbterPhp:Events';
    const CACHE_KEY_MIDDLEWARE         = 'AbterPhp:Middleware';
    const CACHE_KEY_MIGRATION_PATHS    = 'AbterPhp:MigrationPaths';

    /** @var string[] */
    protected $sourceRoots;

    /** @var ICacheBridge|null */
    protected $cacheBridge;

    /** @var array|null */
    protected $modules;

    /**
     * Manager constructor.
     *
     * @param array             $sourceRoots
     * @param ICacheBridge|null $cacheBridge
     */
    public function __construct(array $sourceRoots, ?ICacheBridge $cacheBridge = null)
    {
        $this->sourceRoots = $sourceRoots;
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

        $this->modules = $this->loadModules();

        return $this;
    }

    /**
     * @return array
     */
    public function loadModules(): array
    {
        $rawModules = [];
        foreach ($this->findModules() as $path) {
            $rawModules[] = include $path;
        }

        if (count($rawModules) === 0) {
            return [];
        }

        return $this->sortModules($rawModules);
    }

    /**
     * @return array
     */
    public function sortModules(array $rawModules, array $sortedIds = []): array
    {
        $sortedCount    = count($sortedIds);
        $modules        = [];
        $skippedModules = [];
        foreach ($rawModules as $rawModule) {
            foreach ($rawModule[Module::DEPENDENCIES] as $dep) {
                if (!isset($sortedIds[$dep])) {
                    $skippedModules[] = $rawModule;
                    continue 2;
                }
            }
            $sortedIds[$rawModule[Module::IDENTIFIER]] = $rawModule[Module::IDENTIFIER];

            $modules[] = $rawModule;
        }

        if ($sortedCount === count($sortedIds)) {
            throw new \LogicException('Not able to determine module order. Likely circular dependency found.');
        }

        if ($skippedModules) {
            $modules = array_merge($modules, $this->sortModules($skippedModules, $sortedIds));
        }

        return $modules;
    }

    /**
     * @return array
     */
    public function findModules(): array
    {
        $paths = [];

        foreach ($this->sourceRoots as $root) {
            $paths = array_merge($paths, $this->scanDirectories(new \DirectoryIterator($root)));
        }

        return $paths;
    }

    /**
     * @return array
     */
    public function scanDirectories(\DirectoryIterator $directoryIterator): array
    {
        $paths = [];
        foreach ($directoryIterator as $fileInfo) {
            if ($fileInfo->isDot() || !$fileInfo->isFile()) {
                continue;
            }
            if ($fileInfo->getFilename() === static::MODULE_FILE_NAME) {
                return [$fileInfo->getRealPath()];
            }
        }

        foreach ($directoryIterator as $fileInfo) {
            if ($fileInfo->isDot() || !$fileInfo->isDir()) {
                continue;
            }

            $paths = array_merge($paths, $this->scanDirectories(new \DirectoryIterator($fileInfo->getRealPath())));
        }

        return $paths;
    }
}
