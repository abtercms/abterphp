<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Collection;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\Grid\Cell\Cell;
use AbterPhp\Framework\Grid\Row\Row;
use AbterPhp\Framework\I18n\ITranslator;

class Body extends Rows
{
    const TBODY = 'tbody';

    /** @var array */
    protected $getters;

    /** @var array */
    protected $rowArguments;

    /** @var Actions|null */
    protected $actions;

    /** @var ITranslator */
    protected $translator;

    /**
     * Body constructor.
     *
     * @param array        $getters
     * @param array        $rowArguments
     * @param Actions|null $actions
     * @param ITranslator  $translator
     */
    public function __construct(
        array $getters,
        array $rowArguments,
        ?Actions $actions,
        ITranslator $translator
    ) {
        parent::__construct(static::TBODY, []);

        $this->getters      = $getters;
        $this->rowArguments = $rowArguments;
        $this->actions      = $actions;
        $this->translator   = $translator;
    }

    /**
     * @param IStringerEntity[] $entities
     *
     * @return Body
     */
    public function setEntities(array $entities): Body
    {
        foreach ($entities as $entity) {
            $cells = $this->createCells($entity);

            $actions = $this->actions ? $this->actions->duplicate() : null;

            $row = new Row($cells, $actions, $this->rowArguments);
            $row->setEntity($entity);

            $this->components[] = $row;
        }

        return $this;
    }

    /**
     * @param IStringerEntity $entity
     *
     * @return Cells|array
     */
    private function createCells(IStringerEntity $entity)
    {
        $cells = new Cells();

        foreach ($this->getters as $group => $getter) {
            $content = is_callable($getter) ? $getter($entity) : (string)$entity->$getter();

            $cells[] = new Cell($content, $group, $this->attributes, Cell::BODY, $this->translator);
        }

        return $cells;
    }
}
