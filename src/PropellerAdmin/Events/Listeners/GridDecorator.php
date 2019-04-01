<?php

declare(strict_types=1);

namespace AbterPhp\PropellerAdmin\Events\Listeners;

use AbterPhp\Framework\Events\GridReady;
use AbterPhp\PropellerAdmin\Decorator\General;
use AbterPhp\PropellerAdmin\Decorator\Grid;

class GridDecorator
{
    /** @var Grid */
    protected $gridDecorator;

    /** @var General */
    protected $generalDecorator;

    /**
     * GridDecorator constructor.
     *
     * @param Grid    $gridDecorator
     * @param General $generalDecorator
     */
    public function __construct(Grid $gridDecorator, General $generalDecorator)
    {
        $this->gridDecorator    = $gridDecorator;
        $this->generalDecorator = $generalDecorator;
    }

    /**
     * @param GridReady $event
     */
    public function handle(GridReady $event)
    {
        $grid = $event->getGrid();

        $nodes = array_merge([$grid], $grid->getExtendedDescendantNodes());

        $this->generalDecorator->init()->decorate($nodes);
        $this->gridDecorator->init()->decorate($nodes);
    }
}
