<?php

declare(strict_types=1);

namespace AbterPhp\Framework\I18n\Bootstrappers;

use AbterPhp\Framework\Constant\Env;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\I18n\Translator;
use Opulence\Framework\Configuration\Config;
use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\IContainer;
use Opulence\Sessions\ISession;
use Opulence\Views\Compilers\Fortune\ITranspiler;

class I18nBootstrapper extends Bootstrapper
{
    /**
     * @inheritdoc
     */
    public function registerBindings(IContainer $container)
    {
        $this->registerTranslator($container);
        $this->registerViewFunction($container);
    }

    /**
     * @param IContainer $container
     */
    private function registerTranslator(IContainer $container)
    {
        $session = $container->resolve(ISession::class);

        $translationsDir = Config::get('paths', 'resources.lang');
        $defaultLang     = getenv(Env::DEFAULT_LANGUAGE);

        $translator = new Translator($session, $translationsDir, $defaultLang);

        $container->bindInstance(Translator::class, $translator);
        $container->bindInstance(ITranslator::class, $translator);
    }

    /**
     * @param IContainer $container
     */
    private function registerViewFunction(IContainer $container)
    {
        /** @var Translator $translator */
        $translator = $container->resolve(Translator::class);

        /** @var ITranspiler $transpiler */
        $transpiler = $container->resolve(ITranspiler::class);
        $transpiler->registerViewFunction(
            'tr',
            function (string $key, ...$args) use ($translator) {
                return $translator->translateByArgs($key, $args);
            }
        );
    }
}
