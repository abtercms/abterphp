<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Console\Commands\User;

use AbterPhp\Framework\Authorization\CacheManager;
use AbterPhp\Framework\Crypto\Crypto;
use AbterPhp\Admin\Domain\Entities\User;
use AbterPhp\Admin\Orm\UserGroupRepo;
use AbterPhp\Admin\Orm\UserLanguageRepo;
use AbterPhp\Admin\Orm\UserRepo;
use Opulence\Console\Commands\Command;
use Opulence\Console\Requests\Argument;
use Opulence\Console\Requests\ArgumentTypes;
use Opulence\Console\Requests\Option;
use Opulence\Console\Requests\OptionTypes;
use Opulence\Console\Responses\IResponse;
use Opulence\Orm\IUnitOfWork;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Create extends Command
{
    const COMMAND_NAME            = 'user:create';
    const COMMAND_DESCRIPTION     = 'Creates a new user';
    const COMMAND_SUCCESS         = '<success>New user is created.</success>';
    const COMMAND_DRY_RUN_MESSAGE = '<info>Dry run prevented creating new user.</info>';

    const ARGUMENT_USERNAME   = 'username';
    const ARGUMENT_EMAIL      = 'email';
    const ARGUMENT_PASSWORD   = 'password';
    const ARGUMENT_USER_GROUP = 'user-group';
    const ARGUMENT_USER_LANG  = 'lang';

    const OPTION_CAN_LOGIN       = 'can-login';
    const SHORTENED_CAN_LOGIN    = 'l';
    const OPTION_HAS_GRAVATAR    = 'has-gravatar';
    const SHORTENED_HAS_GRAVATAR = 'g';
    const OPTION_DRY_RUN         = 'dry-run';
    const SHORTENED_DRY_RUN      = 'd';

    /** @var UserRepo */
    protected $userRepo;

    /** @var UserGroupRepo */
    protected $userGroupRepo;

    /** @var UserLanguageRepo */
    protected $userLanguageRepo;

    /** @var Crypto */
    protected $crypto;

    /** @var IUnitOfWork */
    protected $unitOfWork;

    /** @var CacheManager */
    protected $cacheManager;

    /**
     * CreateCommand constructor.
     *
     * @param UserRepo         $userRepo
     * @param UserGroupRepo    $userGroupRepo
     * @param UserLanguageRepo $userLanguageRepo
     * @param Crypto           $crypto
     * @param IUnitOfWork      $unitOfWork
     * @param CacheManager     $cacheManager
     */
    public function __construct(
        UserRepo $userRepo,
        UserGroupRepo $userGroupRepo,
        UserLanguageRepo $userLanguageRepo,
        Crypto $crypto,
        IUnitOfWork $unitOfWork,
        CacheManager $cacheManager
    ) {
        $this->userRepo         = $userRepo;
        $this->userGroupRepo    = $userGroupRepo;
        $this->userLanguageRepo = $userLanguageRepo;
        $this->crypto           = $crypto;
        $this->unitOfWork       = $unitOfWork;
        $this->cacheManager     = $cacheManager;

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    protected function define()
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->addArgument(new Argument(static::ARGUMENT_USERNAME, ArgumentTypes::REQUIRED, 'Username'))
            ->addArgument(new Argument(static::ARGUMENT_EMAIL, ArgumentTypes::REQUIRED, 'Email'))
            ->addArgument(new Argument(static::ARGUMENT_PASSWORD, ArgumentTypes::REQUIRED, 'Password'))
            ->addArgument(new Argument(static::ARGUMENT_USER_GROUP, ArgumentTypes::REQUIRED, 'User Group'))
            ->addArgument(new Argument(static::ARGUMENT_USER_LANG, ArgumentTypes::OPTIONAL, 'Language', 'en'))
            ->addOption(new Option(
                static::OPTION_CAN_LOGIN,
                static::SHORTENED_CAN_LOGIN,
                OptionTypes::OPTIONAL_VALUE,
                'Can user log in',
                '1'
            ))
            ->addOption(new Option(
                static::OPTION_HAS_GRAVATAR,
                static::SHORTENED_HAS_GRAVATAR,
                OptionTypes::OPTIONAL_VALUE,
                'Does user have gravatar (https://en.gravatar.com/)',
                '1'
            ))
            ->addOption(new Option(
                static::OPTION_DRY_RUN,
                static::SHORTENED_DRY_RUN,
                OptionTypes::OPTIONAL_VALUE,
                'Dry run (default: 0)',
                '0'
            ))
        ;
    }

    /**
     * @inheritdoc
     */
    protected function doExecute(IResponse $response)
    {
        try {
            $entity = $this->getEntity();

            $password         = (string)$this->getArgumentValue(static::ARGUMENT_PASSWORD);
            $preparedPassword = $this->crypto->prepareSecret($password);
            $packedPassword   = $this->crypto->hashCrypt($preparedPassword);

            $entity->setPassword($packedPassword);
            $this->userRepo->add($entity);
        } catch (\Exception $e) {
            if ($e->getPrevious()) {
                $response->writeln(sprintf('<error>%s</error>', $e->getPrevious()->getMessage()));
            }
            $response->writeln(sprintf('<fatal>%s</fatal>', $e->getMessage()));

            return;
        }

        $dryRun = (bool)$this->getOptionValue(static::OPTION_DRY_RUN);
        if ($dryRun) {
            $this->unitOfWork->dispose();
            $response->writeln(static::COMMAND_DRY_RUN_MESSAGE);

            return;
        }

        try {
            $this->unitOfWork->commit();
            $this->cacheManager->clearAll();
            $response->writeln(static::COMMAND_SUCCESS);
        } catch (\Exception $e) {
            if ($e->getPrevious()) {
                $response->writeln(sprintf('<error>%s</error>', $e->getPrevious()->getMessage()));
            }
            $response->writeln(sprintf('<fatal>%s</fatal>', $e->getMessage()));
        }
    }

    /**
     * @return User
     * @throws \Opulence\Orm\OrmException
     */
    protected function getEntity(): User
    {
        $username     = $this->getArgumentValue(static::ARGUMENT_USERNAME);
        $email        = $this->getArgumentValue(static::ARGUMENT_EMAIL);
        $ugIdentifier = $this->getArgumentValue(static::ARGUMENT_USER_GROUP);
        $ulIdentifier = $this->getArgumentValue(static::ARGUMENT_USER_LANG);
        $canLogin     = (bool)$this->getArgumentValue(static::ARGUMENT_USER_LANG);
        $hasGravatar  = (bool)$this->getArgumentValue(static::ARGUMENT_USER_LANG);

        $userGroup    = $this->userGroupRepo->getByIdentifier($ugIdentifier);
        $userLanguage = $this->userLanguageRepo->getByIdentifier($ulIdentifier);

        return new User(
            0,
            $username,
            $email,
            '',
            $userGroup,
            $userLanguage,
            $canLogin,
            $hasGravatar
        );
    }
}
