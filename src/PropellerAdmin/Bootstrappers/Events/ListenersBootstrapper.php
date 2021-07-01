<?php

declare(strict_types=1);

namespace AbterPhp\PropellerAdmin\Bootstrappers\Events;

use AbterPhp\Framework\Assets\AssetManager;
use AbterPhp\PropellerAdmin\Events\Listeners\AdminDecorator;
use AbterPhp\PropellerAdmin\Events\Listeners\LoginDecorator;
use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\Bootstrappers\ILazyBootstrapper;
use Opulence\Ioc\IContainer;
use Opulence\Ioc\IocException;

class ListenersBootstrapper extends Bootstrapper implements ILazyBootstrapper
{
    protected const MODULE_IDENTIFIER = 'AbterPhp\\PropellerAdmin';

    protected const PROPELLER_PATH = 'propeller/';

    /**
     * @return array
     */
    public function getBindings(): array
    {
        return [
            AdminDecorator::class,
            LoginDecorator::class,
        ];
    }

    /**
     * @inheritdoc
     * @throws IocException
     */
    public function registerBindings(IContainer $container)
    {
        $resourceDir = $this->getResourceDir();

        $header = file_get_contents($resourceDir . 'header.html');
        $footer = file_get_contents($resourceDir . 'footer.html');

        /** @var AssetManager $assetManager */
        $assetManager = $container->resolve(AssetManager::class);

        $adminDecorator = new AdminDecorator($assetManager, $header, $footer);
        $loginDecorator = new LoginDecorator($assetManager, $header, $footer);

        $container->bindInstance(AdminDecorator::class, $adminDecorator);
        $container->bindInstance(LoginDecorator::class, $loginDecorator);
    }

    /**
     * @return string
     */
    protected function getResourceDir(): string
    {
        global $abterModuleManager;

        foreach ($abterModuleManager->getResourcePaths() as $id => $path) {
            if ($id !== static::MODULE_IDENTIFIER) {
                continue;
            }

            return sprintf('%s/%s', $path, static::PROPELLER_PATH);
        }

        return '';
    }
}
