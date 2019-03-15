<?php

declare(strict_types=1);

namespace AbterPhp\Bootstrap4\Events\Bootstrappers;

use AbterPhp\Bootstrap4\Events\Listeners\WebsiteDecorator;
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
