<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Row;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Grid\Action\IAction;
use AbterPhp\Framework\Grid\Cell\Cell;
use AbterPhp\Framework\Grid\Collection\Cells;
use AbterPhp\Framework\Grid\Component\Actions;
use AbterPhp\Framework\Html\Helper\StringHelper;
use AbterPhp\Framework\Html\INode;
use AbterPhp\Framework\Html\NodeContainerTrait;
use AbterPhp\Framework\Html\Tag;
use AbterPhp\Framework\I18n\ITranslator;
use Opulence\Orm\IEntity;

class Row extends Tag implements IRow
{
    const DEFAULT_TAG = Html5::TAG_TR;

    /** @var Cells */
    protected $cells;

    /** @var Actions|null */
    protected $actions;

    /** @var Cell */
    protected $actionCell;

    /** @var IEntity */
    protected $entity;

    use NodeContainerTrait;

    /**
     * Row constructor.
     *
     * @param Cells        $cells
     * @param Actions|null $actions
     * @param string[]     $intents
     * @param array        $attributes
     * @param string|null  $tag
     */
    public function __construct(
        Cells $cells,
        ?Actions $actions = null,
        array $intents = [],
        array $attributes = [],
        ?string $tag = null
    ) {
        parent::__construct(null, $intents, $attributes, $tag);

        $this->cells   = $cells;
        $this->actions = $actions;

        if ($actions) {
            $this->actionCell = new Cell($this->actions, Cell::GROUP_ACTIONS, [Cell::INTENT_ACTIONS]);
        }
    }

    /**
     * @return string
     */
    public function getCells(): Cells
    {
        return $this->cells;
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

        /** @var IAction $action */
        foreach ($this->actions as $action) {
            $action->setEntity($entity);
        }
    }

    /**
     * @return INode[]
     */
    public function getNodes(): array
    {
        if ($this->actionCell) {
            return [$this->cells, $this->actionCell];
        }

        return [$this->cells];
    }

    /**
     * @param ITranslator|null $translator
     *
     * @return $this
     */
    public function setTranslator(?ITranslator $translator): INode
    {
        $this->translator = $translator;

        $nodes = $this->getNodes();
        foreach ($nodes as $node) {
            $node->setTranslator($translator);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $content = (string)$this->cells;

        if ($this->actionCell) {
            $content .= (string)$this->actionCell;
        }

        $content = StringHelper::wrapInTag($content, $this->tag, $this->attributes);

        return $content;
    }
}
