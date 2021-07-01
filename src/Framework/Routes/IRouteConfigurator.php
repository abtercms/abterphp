<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Routes;

use Opulence\Routing\Router;

interface IRouteConfigurator
{
    public function setRoutes(Router $router);
}
