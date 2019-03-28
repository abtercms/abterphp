<?php

declare(strict_types=1);

namespace AbterPhp\PropellerAdmin\Bootstrappers\Html\Component;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Html\Component\ButtonFactory;
use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\Bootstrappers\ILazyBootstrapper;
use Opulence\Ioc\IContainer;
use Opulence\Routing\Urls\UrlGenerator;

class ButtonFactoryBootstrapper extends Bootstrapper implements ILazyBootstrapper
{
    /** @var array */
    protected $iconAttributes = [
        Html5::ATTR_CLASS => 'material-icons media-left media-middle',
    ];

    /** @var array */
    protected $textAttributes = [
        Html5::ATTR_CLASS => 'media-body',
    ];

    /**
     * @return array
     */
    public function getBindings(): array
    {
        return [
            ButtonFactory::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function registerBindings(IContainer $container)
    {
        /** @var UrlGenerator $urlGenerator */
        $urlGenerator = $container->resolve(UrlGenerator::class);

        $buttonFactory = new ButtonFactory($urlGenerator, $this->textAttributes, $this->iconAttributes);

        $container->bindInstance(ButtonFactory::class, $buttonFactory);
    }
}
