<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Console\Commands\User;

use AbterPhp\Admin\Orm\UserRepo;
use AbterPhp\Framework\Authorization\CacheManager;
use AbterPhp\Framework\Database\PDO\UnitOfWork;
use Opulence\Console\Commands\Command;
use Opulence\Console\Requests\Argument;
use Opulence\Console\Requests\ArgumentTypes;
use Opulence\Console\Requests\Option;
use Opulence\Console\Requests\OptionTypes;
use Opulence\Console\Responses\IResponse;
use Opulence\Console\StatusCodes;

class Delete extends Command
{
    protected const COMMAND_NAME            = 'user:delete';
    protected const COMMAND_DESCRIPTION     = 'Deletes an existing user';
    protected const COMMAND_SUCCESS         = '<success>Existing user is deleted.</success>';
    protected const COMMAND_DRY_RUN_MESSAGE = '<info>Dry run prevented deleting existing user.</info>';

    protected const ARGUMENT_IDENTIFIER = 'identifier';

    protected const OPTION_DRY_RUN    = 'dry-run';
    protected const SHORTENED_DRY_RUN = 'd';

    protected UserRepo $userRepo;

    protected UnitOfWork $unitOfWork;

    protected CacheManager $cacheManager;

    /**
     * CreateUserCommand constructor.
     *
     * @param UserRepo     $userRepo
     * @param UnitOfWork   $unitOfWork
     * @param CacheManager $cacheManager
     */
    public function __construct(UserRepo $userRepo, UnitOfWork $unitOfWork, CacheManager $cacheManager)
    {
        $this->userRepo     = $userRepo;
        $this->unitOfWork   = $unitOfWork;
        $this->cacheManager = $cacheManager;

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    protected function define()
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->addArgument(
                new Argument(
                    static::ARGUMENT_IDENTIFIER,
                    ArgumentTypes::REQUIRED,
                    'Identifier (Email or Username)'
                )
            )
            ->addOption(
                new Option(
                    static::OPTION_DRY_RUN,
                    static::SHORTENED_DRY_RUN,
                    OptionTypes::OPTIONAL_VALUE,
                    'Dry run (default: 0)',
                    '0'
                )
            );
    }

    /**
     * @inheritdoc
     */
    protected function doExecute(IResponse $response)
    {
        $identifier = $this->getArgumentValue(static::ARGUMENT_IDENTIFIER);
        $dryRun     = $this->getOptionValue(static::OPTION_DRY_RUN);

        $entity = $this->userRepo->find($identifier);
        if (!$entity) {
            $response->writeln(sprintf('<fatal>User not found</fatal>'));

            return StatusCodes::ERROR;
        }

        $this->userRepo->delete($entity);

        if ($dryRun) {
            $this->unitOfWork->dispose();
            $response->writeln(static::COMMAND_DRY_RUN_MESSAGE);

            return StatusCodes::OK;
        }

        try {
            $this->unitOfWork->commit();
            $this->cacheManager->clearAll();
        } catch (\Exception $e) {
            if ($e->getPrevious()) {
                $response->writeln(sprintf('<error>%s</error>', $e->getPrevious()->getMessage()));
            }
            $response->writeln(sprintf('<fatal>%s</fatal>', $e->getMessage()));

            return StatusCodes::FATAL;
        }

        $response->writeln(static::COMMAND_SUCCESS);

        return StatusCodes::OK;
    }
}
