<?php

declare(strict_types=1);

namespace AbterPhp\Propeller\Bootstrappers\Events;

use AbterPhp\Framework\Assets\AssetManager;
use AbterPhp\Propeller\Events\Listeners\AdminDecorator;
use AbterPhp\Propeller\Events\Listeners\LoginDecorator;
use Opulence\Framework\Configuration\Config;
use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\IContainer;

class Listeners extends Bootstrapper
{
    /**
     * @inheritdoc
     */
    public function registerBindings(IContainer $container)
    {
        $resourceDir = Config::get('paths', 'resources.propeller');

        $header = file_get_contents($resourceDir . '/header.html');
        $footer = file_get_contents($resourceDir . '/footer.html');

        /** @var AssetManager $assetManager */
        $assetManager = $container->resolve(AssetManager::class);

        $adminDecorator = new AdminDecorator($assetManager, $header, $footer);
        $loginDecorator = new LoginDecorator($assetManager, $header, $footer);

        $container->bindInstance(AdminDecorator::class, $adminDecorator);
        $container->bindInstance(LoginDecorator::class, $loginDecorator);
    }
}
