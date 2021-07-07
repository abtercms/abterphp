<?php

declare(strict_types=1);

namespace AbterPhp\Files\Service\RepoGrid;

use AbterPhp\Admin\Service\RepoGrid\RepoGridAbstract;
use AbterPhp\Files\Grid\Factory\FileCategory as GridFactory;
use AbterPhp\Files\Orm\FileCategoryRepo as Repo;
use Casbin\Enforcer;

class FileCategory extends RepoGridAbstract
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
