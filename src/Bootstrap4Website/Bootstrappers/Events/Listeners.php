<?php

declare(strict_types=1);

namespace AbterPhp\Bootstrap4Website\Bootstrappers\Events;

use AbterPhp\Bootstrap4Website\Events\Listeners\WebsiteDecorator;
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
        $resourceDir = Config::get('paths', 'resources.bootstrap4');

        $header = file_get_contents($resourceDir . '/header.html');
        $footer = file_get_contents($resourceDir . '/footer.html');

        $websiteDecorator = new WebsiteDecorator($header, $footer);

        $container->bindInstance(WebsiteDecorator::class, $websiteDecorator);
    }
}
