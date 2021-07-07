<?php

declare(strict_types=1);

namespace AbterPhp\Website\Service\RepoGrid;

use AbterPhp\Admin\Service\RepoGrid\RepoGridAbstract;
use AbterPhp\Website\Grid\Factory\Block as GridFactory;
use AbterPhp\Website\Orm\BlockRepo as Repo;
use Casbin\Enforcer;

class Block extends RepoGridAbstract
{
    /**
     * Block constructor.
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
