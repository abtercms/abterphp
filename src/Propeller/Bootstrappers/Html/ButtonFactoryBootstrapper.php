<?php

declare(strict_types=1);

namespace AbterPhp\Propeller\Bootstrappers\Html;

use AbterPhp\Framework\Html\ButtonFactory;
use AbterPhp\Framework\Html\Component\Button;
use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\Bootstrappers\ILazyBootstrapper;
use Opulence\Ioc\IContainer;
use Opulence\Routing\Urls\UrlGenerator;

class ButtonFactoryBootstrapper extends Bootstrapper implements ILazyBootstrapper
{
    /** @var array */
    protected $iconAttributes = [
        Button::ATTRIBUTE_CLASS => 'material-icons media-left media-middle',
    ];

    /** @var array */
    protected $textAttributes = [
        Button::ATTRIBUTE_CLASS => 'media-body',
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

        $buttonFactory = new ButtonFactory($urlGenerator, $this->iconAttributes, $this->textAttributes);

        $container->bindInstance(ButtonFactory::class, $buttonFactory);
    }
}
