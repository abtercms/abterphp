<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Http\Middleware;

use AbterPhp\Admin\Constant\Env;
use AbterPhp\Framework\Security\SecurityException;
use Closure;
use Opulence\Cache\ICacheBridge;
use Opulence\Environments\Environment;
use Opulence\Http\Requests\Request;
use Opulence\Http\Responses\Response;
use Opulence\Routing\Middleware\IMiddleware;

class Security implements IMiddleware
{
    const KEY = 'abteradmin:security';

    /** @var ICacheBridge */
    protected $cacheBridge;

    /**
     * Security constructor.
     *
     * @param ICacheBridge $cacheBridge
     */
    public function __construct(ICacheBridge $cacheBridge)
    {
        $this->cacheBridge = $cacheBridge;
    }

    // $next consists of the next middleware in the pipeline
    public function handle(Request $request, Closure $next): Response
    {
        if (getenv(\AbterPhp\Framework\Constant\Env::ENV_NAME) !== Environment::PRODUCTION) {
            return $next($request);
        }

        try {
            if ($this->cacheBridge->get(static::KEY)) {
                return $next($request);
            }
        } catch (\Exception $e) {
        }

        $this->checkRoutes();

        $this->cacheBridge->set(static::KEY, true, PHP_INT_MAX);

        return $next($request);
    }

    private function checkRoutes()
    {
        if (getenv(Env::ADMIN_LOGIN_PATH) === '/admin-iddqd') {
            throw new SecurityException('Invalid ADMIN_LOGIN_PATH environment variable.');
        }

        if (getenv(Env::ADMIN_BASE_PATH) === '/login-iddqd') {
            throw new SecurityException('Invalid ADMIN_BASE_PATH environment variable.');
        }

        if (getenv(Env::API_BASE_PATH) === '/api-iddqd') {
            throw new SecurityException('Invalid ADMIN_BASE_PATH environment variable.');
        }
    }
}
