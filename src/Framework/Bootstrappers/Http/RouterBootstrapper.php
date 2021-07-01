<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Bootstrappers\Http;

use Opulence\Framework\Configuration\Config;
use Opulence\Framework\Routing\Bootstrappers\RouterBootstrapper as BaseBootstrapper;
use Opulence\Ioc\IContainer;
use Opulence\Ioc\IocException;
use Opulence\Routing\Router;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class RouterBootstrapper extends BaseBootstrapper
{
    /** @var string[]  */
    protected array $routeConfigurators = [];

    protected ?IContainer $container = null;

    /**
     * @return IContainer|null
     */
    public function getContainer(): ?IContainer
    {
        global $container;

        if (null === $this->container) {
            $this->container = $container;
        }

        return $this->container;
    }

    /**
     * @param IContainer|null $container
     */
    public function setContainer(?IContainer $container): void
    {
        $this->container = $container;
    }

    /**
     * @return string[]
     */
    public function getRouteConfigurators(): array
    {
        global $abterModuleManager;

        if ($this->routeConfigurators !== []) {
            return $this->routeConfigurators;
        }

        $this->routeConfigurators = $abterModuleManager->getRouteConfigurators();

        return $this->routeConfigurators;
    }

    /**
     * @param string[] $routeConfigurators
     *
     * @return $this
     */
    public function setRouteConfigurators(array $routeConfigurators): self
    {
        $this->routeConfigurators = $routeConfigurators;

        return $this;
    }


    /**
     * Configures the router, which is useful for things like caching
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param Router $router The router to configure
     *
     * @throws IocException
     */
    protected function configureRouter(Router $router): void
    {
        $container = $this->getContainer();

        $httpConfigPath   = Config::get('paths', 'config.http');
        $routesConfigPath = "$httpConfigPath/routes.php";

        require $routesConfigPath;

        foreach ($this->getRouteConfigurators() as $className) {
            $routeConfigurator = $container->resolve($className);

            $routeConfigurator->setRoutes($router);
        }
    }
}
