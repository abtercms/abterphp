<?php

declare(strict_types=1);

namespace AbterPhp\Files\Service\RepoGrid;

use AbterPhp\Framework\Service\RepoGrid\RepoGridAbstract;
use AbterPhp\Files\Grid\Factory\File as GridFactory;
use Casbin\Enforcer;
use AbterPhp\Framework\Databases\Queries\FoundRows;
use AbterPhp\Files\Orm\FileRepo as Repo;

class File extends RepoGridAbstract
{
    /**
     * File constructor.
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
