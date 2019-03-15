<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Service\RepoGrid;

use AbterPhp\Framework\Service\RepoGrid\RepoGridAbstract;
use AbterPhp\Admin\Grid\Factory\UserGroup as GridFactory;
use Casbin\Enforcer;
use AbterPhp\Framework\Databases\Queries\FoundRows;
use AbterPhp\Admin\Orm\UserGroupRepo as Repo;

class UserGroup extends RepoGridAbstract
{
    /**
     * UserGroup constructor.
     *
     * @param Enforcer    $enforcer
     * @param Repo        $repo
     * @param FoundRows   $foundRows
     * @param GridFactory $gridFactory
     */
    public function __construct(Enforcer $enforcer, Repo $repo, FoundRows $foundRows, GridFactory $gridFactory)
    {
        parent::__construct($enforcer, $repo, $foundRows, $gridFactory);
    }
}
