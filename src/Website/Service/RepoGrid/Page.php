<?php

declare(strict_types=1);

namespace AbterPhp\Website\Service\RepoGrid;

use AbterPhp\Admin\Service\RepoGrid\RepoGridAbstract;
use AbterPhp\Website\Grid\Factory\Page as GridFactory;
use AbterPhp\Website\Orm\PageRepo as Repo;
use Casbin\Enforcer;

class Page extends RepoGridAbstract
{
    /**
     * Page constructor.
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
