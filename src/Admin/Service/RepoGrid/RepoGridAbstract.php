<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Service\RepoGrid;

use AbterPhp\Framework\Grid\Factory\IBase as GridFactory;
use AbterPhp\Framework\Grid\IGrid;
use AbterPhp\Framework\Http\Service\RepoGrid\IRepoGrid;
use AbterPhp\Framework\Orm\IGridRepo;
use Casbin\Enforcer;
use Opulence\Http\Collection;

abstract class RepoGridAbstract implements IRepoGrid
{
    protected Enforcer $enforcer;

    protected IGridRepo $repo;

    protected GridFactory $gridFactory;

    /**
     * GridAbstract constructor.
     *
     * @param Enforcer    $enforcer
     * @param IGridRepo   $repo
     * @param GridFactory $gridFactory
     */
    public function __construct(
        Enforcer $enforcer,
        IGridRepo $repo,
        GridFactory $gridFactory
    ) {
        $this->enforcer    = $enforcer;
        $this->repo        = $repo;
        $this->gridFactory = $gridFactory;
    }

    /**
     * @param Collection $query
     * @param string     $baseUrl
     *
     * @return IGrid
     */
    public function createAndPopulate(Collection $query, string $baseUrl): IGrid
    {
        $grid = $this->gridFactory->createGrid($query->getAll(), $baseUrl);

        $limit  = $grid->getPageSize();
        $offset = $this->getOffset($query, $limit);

        $sortBy  = $this->getSortConditions($grid);
        $filters = $this->getWhereConditions($grid);

        $entities = $this->repo->getPage($offset, $limit, $sortBy, $filters);
        $maxCount = $this->repo->getCount($filters);

        $grid->setTotalCount($maxCount)->setEntities($entities);

        return $grid;
    }

    /**
     * @param IGrid $grid
     *
     * @return array
     */
    protected function getSortConditions(IGrid $grid): array
    {
        return $grid->getSortConditions();
    }

    /**
     * @param IGrid $grid
     *
     * @return array
     */
    protected function getWhereConditions(IGrid $grid): array
    {
        return $grid->getWhereConditions();
    }

    /**
     * @param Collection $query
     * @param int        $pageSize
     *
     * @return int
     */
    protected function getOffset(Collection $query, int $pageSize): int
    {
        $page = (int)$query->get('page', 1);

        return ($page - 1) * $pageSize;
    }
}
