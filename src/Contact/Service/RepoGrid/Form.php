<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Service\RepoGrid;

use AbterPhp\Admin\Service\RepoGrid\RepoGridAbstract;
use AbterPhp\Contact\Grid\Factory\Form as GridFactory;
use AbterPhp\Contact\Orm\FormRepo as Repo;
use Casbin\Enforcer;

class Form extends RepoGridAbstract
{
    /**
     * Form constructor.
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
