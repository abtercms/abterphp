<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Bootstrappers\Validation;

use AbterPhp\Framework\Constant\Env;
use AbterPhp\Admin\Validation\Factory\User;
use AbterPhp\Admin\Validation\Factory\UserGroup;
use InvalidArgumentException;
use Opulence\Framework\Configuration\Config;
use Opulence\Framework\Validation\Bootstrappers\ValidatorBootstrapper as BaseBootstrapper;
use Opulence\Ioc\IContainer;
use Opulence\Validation\Rules\Errors\ErrorTemplateRegistry;

/**
 * Defines the validator bootstrapper
 */
class ValidatorBootstrapper extends BaseBootstrapper
{
    /**
     * @var array
     */
    protected $validatorFactories = [
        User::class,
        UserGroup::class,
    ];

    /**
     * @inheritdoc
     */
    public function getBindings(): array
    {
        $bindings = array_merge(
            parent::getBindings(),
            $this->validatorFactories
        );

        return $bindings;
    }

    /**
     * @inheritdoc
     */
    public function registerBindings(IContainer $container)
    {
        parent::registerBindings($container);
    }

    /**
     * @SuppressWarnings(PHPMD.LongVariable)
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * Registers the error templates
     *
     * @param ErrorTemplateRegistry $errorTemplateRegistry The registry to register to
     *
     * @throws InvalidArgumentException Thrown if the config was invalid
     */
    protected function registerErrorTemplates(ErrorTemplateRegistry $errorTemplateRegistry)
    {
        $config = require sprintf(
            '%s/%s/validation.php',
            Config::get('paths', 'resources.lang'),
            getenv(Env::DEFAULT_LANGUAGE)
        );

        $errorTemplateRegistry->registerErrorTemplatesFromConfig($config);
    }
}
