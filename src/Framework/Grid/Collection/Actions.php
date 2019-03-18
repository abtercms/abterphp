<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Collection;

use AbterPhp\Framework\Grid\Action\IAction;
use AbterPhp\Framework\Html\Collection\Collection;
use InvalidArgumentException;
use LogicException;

class Actions extends Collection
{
    /** @var IAction[] */
    protected $components = [];

    /** @var string */
    protected $componentClass = IAction::class;

    /**
     * @return Actions
     */
    public function duplicate(): Actions
    {
        $actionsCopy = new Actions();

        foreach ($this->components as $action) {
            $actionCopy    = $action->duplicate();
            $actionsCopy[] = $actionCopy;
        }

        return $actionsCopy;
    }
}
