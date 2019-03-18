<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Events;

use AbterPhp\Framework\Grid\Grid;

class GridReady
{
    /** @var Grid */
    private $grid;

    /**
     * GridReady constructor.
     *
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
    }

    /**
     * @return Grid
     */
    public function getGrid(): Grid
    {
        return $this->grid;
    }
}
