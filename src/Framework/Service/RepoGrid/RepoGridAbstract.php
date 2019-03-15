<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Service\RepoGrid;

use AbterPhp\Framework\Grid\Factory\IBase as GridFactory;
use AbterPhp\Framework\Grid\IGrid;
use AbterPhp\Framework\I18n\ITranslator;
use Casbin\Enforcer;
use AbterPhp\Framework\Databases\Queries\FoundRows;
use AbterPhp\Framework\Orm\IGridRepo;
use Opulence\Http\Collection;

abstract class RepoGridAbstract implements IRepoGrid
{
    /** @var Enforcer */
    protected $enforcer;

    /** @var IGridRepo */
    protected $repo;

    /** @var FoundRows */
    protected $foundRows;

    /** @var GridFactory */
    protected $gridFactory;

    /**
     * GridAbstract constructor.
     *
     * @param Enforcer          $enforcer
     * @param ITranslator       $translator
     * @param IGridRepo         $repo
     * @param FoundRows         $foundRows
     * @param GridFactory       $gridFactory
     */
    public function __construct(
        Enforcer $enforcer,
        IGridRepo $repo,
        FoundRows $foundRows,
        GridFactory $gridFactory
    ) {
        $this->enforcer          = $enforcer;
        $this->repo              = $repo;
        $this->foundRows         = $foundRows;
        $this->gridFactory       = $gridFactory;
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

        $pageSize  = $grid->getPageSize();
        $limitFrom = $this->getOffset($query, $pageSize);

        $sortBy = $grid->getSortConditions();
        $where  = $grid->getWhereConditions();
        $params = $grid->getSqlParams();

        $entities = $this->repo->getPage($limitFrom, $pageSize, $sortBy, $where, $params);
        $maxCount = $this->foundRows->get();

        $grid->setTotalCount($maxCount)->setEntities($entities);

        return $grid;
    }

    /**
     * @param Collection $query
     * @param int        $pageSize
     *
     * @return int
     */
    protected function getOffset(Collection $query, int $pageSize): int
    {
        $page   = (int)$query->get('page', 1);
        $offset = ($page - 1) * $pageSize;

        return $offset;
    }
}
