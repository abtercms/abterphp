<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Template\Bootstrappers;

use AbterPhp\Framework\Constant\Event;
use AbterPhp\Framework\Events\TemplateEngineReady;
use AbterPhp\Framework\Template\CacheManager;
use AbterPhp\Framework\Template\TemplateEngine;
use AbterPhp\Framework\Template\TemplateFactory;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\Bootstrappers\ILazyBootstrapper;
use Opulence\Ioc\IContainer;

class TemplateEngineBootstrapper extends Bootstrapper implements ILazyBootstrapper
{
    /**
     * @return array
     */
    public function getBindings(): array
    {
        return [
            TemplateEngine::class,
        ];
    }

    /**
     * @param IContainer $container
     *
     * @throws \Opulence\Ioc\IocException
     */
    public function registerBindings(IContainer $container)
    {
        /** @var IEventDispatcher $eventDispatcher */
        $eventDispatcher = $container->resolve(IEventDispatcher::class);

        /** @var TemplateFactory $templateFactory */
        $templateFactory = $container->resolve(TemplateFactory::class);

        /** @var CacheManager $cacheManager */
        $cacheManager = $container->resolve(CacheManager::class);

        $templateEngine = new TemplateEngine($templateFactory, $cacheManager);
        $eventDispatcher->dispatch(Event::TEMPLATE_ENGINE_READY, new TemplateEngineReady($templateEngine));

        $container->bindInstance(TemplateEngine::class, $templateEngine);
    }
}
