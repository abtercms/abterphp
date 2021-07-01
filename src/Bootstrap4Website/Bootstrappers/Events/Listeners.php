<?php

declare(strict_types=1);

namespace AbterPhp\Bootstrap4Website\Bootstrappers\Events;

use AbterPhp\Bootstrap4Website\Events\Listeners\WebsiteDecorator;
use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\Bootstrappers\ILazyBootstrapper;
use Opulence\Ioc\IContainer;

class Listeners extends Bootstrapper implements ILazyBootstrapper
{
    protected const MODULE_IDENTIFIER = 'AbterPhp\\Bootstrap4Website';

    protected const BOOTSTRAP4_PATH = 'bootstrap4/';

    /**
     * @return array
     */
    public function getBindings(): array
    {
        return [
            WebsiteDecorator::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function registerBindings(IContainer $container)
    {
        $resourceDir = $this->getResourceDir();

        $header = file_get_contents($resourceDir . 'header.html');
        $footer = file_get_contents($resourceDir . 'footer.html');

        $websiteDecorator = new WebsiteDecorator($header, $footer);

        $container->bindInstance(WebsiteDecorator::class, $websiteDecorator);
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

            return sprintf('%s/%s', $path, static::BOOTSTRAP4_PATH);
        }

        return '';
    }
}
