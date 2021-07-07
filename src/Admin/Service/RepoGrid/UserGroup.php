<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Service\RepoGrid;

use AbterPhp\Admin\Grid\Factory\UserGroup as GridFactory;
use AbterPhp\Admin\Orm\UserGroupRepo as Repo;
use Casbin\Enforcer;

class UserGroup extends RepoGridAbstract
{
    /**
     * UserGroup constructor.
     *
     * @param Enforcer    $enforcer
     * @param Repo        $repo
     * @param GridFactory $gridFactory
     */
    public function __construct(Enforcer $enforcer, Repo $repo, GridFactory $gridFactory)
    {
        parent::__construct($enforcer, $repo, $gridFactory);
    }
}
