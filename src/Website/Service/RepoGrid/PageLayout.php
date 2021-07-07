<?php

declare(strict_types=1);

namespace AbterPhp\Website\Service\RepoGrid;

use AbterPhp\Admin\Service\RepoGrid\RepoGridAbstract;
use AbterPhp\Website\Grid\Factory\PageLayout as GridFactory;
use AbterPhp\Website\Orm\PageLayoutRepo as Repo;
use Casbin\Enforcer;

class PageLayout extends RepoGridAbstract
{
    /**
     * PageLayout constructor.
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
