<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Console\Commands\UserGroup;

use AbterPhp\Admin\Domain\Entities\UserGroup as Entity;
use AbterPhp\Admin\Orm\UserGroupRepo;
use Opulence\Console\Commands\Command;
use Opulence\Console\Responses\IResponse;

class ListCommand extends Command
{
    protected const COMMAND_NAME        = 'usergroup:list';
    protected const COMMAND_DESCRIPTION = 'List available user groups';

    protected UserGroupRepo $userGroupRepo;

    /**
     * ListCommand constructor.
     *
     * @param UserGroupRepo $userGroupRepo
     */
    public function __construct(UserGroupRepo $userGroupRepo)
    {
        $this->userGroupRepo = $userGroupRepo;

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    protected function define()
    {
        $this->setName(static::COMMAND_NAME)->setDescription(static::COMMAND_DESCRIPTION);
    }

    /**
     * @inheritdoc
     */
    protected function doExecute(IResponse $response)
    {
        /** @var Entity[] $userGroups */
        $userGroups = $this->userGroupRepo->getAll();

        foreach ($userGroups as $userGroup) {
            assert($userGroup instanceof Entity);
            $response->writeln($userGroup->getIdentifier());
        }
    }
}
