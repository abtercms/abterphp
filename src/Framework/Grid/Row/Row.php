<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Row;

use AbterPhp\Framework\Grid\Cell\Cell;
use AbterPhp\Framework\Grid\Collection\Actions;
use AbterPhp\Framework\Grid\Collection\Cells;
use AbterPhp\Framework\Html\Component\Component;
use Opulence\Orm\IEntity;

class Row extends Component implements IRow
{
    const TAG = 'tr';

    /** @var Cells */
    protected $cells;

    /** @var Actions */
    protected $actions;

    /** @var IEntity */
    protected $entity;

    /**
     * Row constructor.
     *
     * @param Cells        $cells
     * @param Actions|null $actions
     * @param array        $attributes
     * @param string       $tag
     */
    public function __construct(Cells $cells, Actions $actions = null, array $attributes = [], string $tag = self::TAG)
    {
        $this->cells   = $cells;
        $this->actions = $actions;

        parent::__construct('', $tag, $attributes);

        $this->appendToAttribute(Component::ATTRIBUTE_CLASS, $tag);
    }

    /**
     * @return string
     */
    public function getCells(): Cells
    {
        return $this->cells;
    }

    /**
     * @return Actions
     */
    public function getActions(): Actions
    {
        return $this->actions;
    }

    /**
     * @return IEntity
     */
    public function getEntity(): IEntity
    {
        return $this->entity;
    }

    /**
     * @param IEntity $entity
     */
    public function setEntity(IEntity $entity)
    {
        $this->entity = $entity;

        if (null === $this->actions) {
            return;
        }

        foreach ($this->actions as $action) {
            $action->setEntity($entity);
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $this->content = (string)$this->cells;

        if ($this->actions) {
            $this->content .= new Cell((string)$this->actions, 'actions');
        }

        $return = parent::__toString();

        return $return;
    }
}
