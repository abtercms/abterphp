<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Row;

use AbterPhp\Framework\Grid\Collection\Actions;
use AbterPhp\Framework\Grid\Collection\Cells;
use AbterPhp\Framework\Html\Component\IComponent;
use Opulence\Orm\IEntity;

interface IRow extends IComponent
{
    public function getCells(): Cells;

    public function getActions(): Actions;

    public function setEntity(IEntity $entity);

    public function getEntity(): IEntity;
}
