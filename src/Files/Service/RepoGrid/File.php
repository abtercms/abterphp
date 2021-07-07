<?php

declare(strict_types=1);

namespace AbterPhp\Files\Service\RepoGrid;

use AbterPhp\Admin\Service\RepoGrid\RepoGridAbstract;
use AbterPhp\Files\Grid\Factory\File as GridFactory;
use AbterPhp\Files\Orm\FileRepo as Repo;
use Casbin\Enforcer;

class File extends RepoGridAbstract
{
    /**
     * File constructor.
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
