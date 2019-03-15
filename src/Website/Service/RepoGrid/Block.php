<?php

declare(strict_types=1);

namespace AbterPhp\Website\Service\RepoGrid;

use AbterPhp\Framework\Service\RepoGrid\RepoGridAbstract;
use AbterPhp\Website\Grid\Factory\Block as GridFactory;
use Casbin\Enforcer;
use AbterPhp\Framework\Databases\Queries\FoundRows;
use AbterPhp\Website\Orm\BlockRepo as Repo;

class Block extends RepoGridAbstract
{
    /**
     * Block constructor.
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
