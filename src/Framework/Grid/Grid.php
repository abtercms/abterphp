<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid;

use AbterPhp\Framework\Grid\Collection\Actions;
use AbterPhp\Framework\Grid\Collection\Filters;
use AbterPhp\Framework\Grid\Pagination\IPagination;
use AbterPhp\Framework\Grid\Table\ITable;
use AbterPhp\Framework\Html\Component\Tag;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;

class Grid extends Tag implements IGrid
{
    /**
     *   %1$s - filter
     *   %2$s - actions
     *   %3$s - table
     */
    const TEMPLATE_CONTENT = '%1$s%4$s%2$s%3$s%4$s';

    const TAG_GRID    = 'div';
    const TAG_FILTER  = 'div';
    const TAG_ACTIONS = 'div';

    const ATTRIBUTE_GRID_CLASS    = 'grid';
    const ATTRIBUTE_FILTER_CLASS  = 'grid-filters';
    const ATTRIBUTE_ACTIONS_CLASS = 'grid-actions';

    /** @var string */
    protected $containerClass = '';

    /** @var ITable */
    protected $table;

    /** @var IPagination */
    protected $pagination;

    /** @var Filters */
    protected $filters;

    /** @var Actions */
    protected $actions;

    /**
     * @param ITable           $rows
     * @param IPagination      $pagination
     * @param Filters|null     $filters
     * @param Actions|null     $massActions
     * @param array            $attributes
     * @param ITranslator|null $translator
     */
    public function __construct(
        ITable $table,
        IPagination $pagination = null,
        Filters $filters = null,
        Actions $actions = null,
        array $attributes = [],
        ITranslator $translator = null
    ) {
        $this->table      = $table;
        $this->pagination = $pagination;

        parent::__construct('', $attributes, $translator, static::TAG_GRID);

        $this->appendToAttribute(Tag::ATTRIBUTE_CLASS, static::ATTRIBUTE_GRID_CLASS);

        if ($actions) {
            $this->actions = $actions;
            $this->actions->appendToAttribute(Tag::ATTRIBUTE_CLASS, static::ATTRIBUTE_ACTIONS_CLASS);
        }

        if ($filters) {
            $this->filters = $filters;
            $this->filters->appendToAttribute(Tag::ATTRIBUTE_CLASS, static::ATTRIBUTE_FILTER_CLASS);
        }
    }

    /**
     * @return int
     */
    public function getPageSize(): int
    {
        $pageSize = $this->pagination->getPageSize();

        return $pageSize;
    }

    /**
     * @return array
     */
    public function getSortConditions(): array
    {
        return $this->table->getSortConditions();
    }

    /**
     * @return array
     */
    public function getWhereConditions(): array
    {
        return $this->filters->getWhereConditions();
    }

    /**
     * @return array
     */
    public function getSqlParams(): array
    {
        $tableParams   = $this->table->getSqlParams();
        $filtersParams = $this->filters->getSqlParams();

        return array_merge($tableParams, $filtersParams);
    }

    /**
     * @param int $totalCount
     *
     * @return $this
     */
    public function setTotalCount(int $totalCount): IGrid
    {
        $this->pagination->setTotalCount($totalCount);

        return $this;
    }

    /**
     * @param IStringerEntity[] $entities
     *
     * @return IGrid
     */
    public function setEntities(array $entities): IGrid
    {
        $this->table->setEntities($entities);

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $filters    = (string)$this->filters;
        $actions    = $this->actions ? (string)$this->actions : '';
        $table      = (string)$this->table;
        $pagination = (string)$this->pagination;

        $this->content = sprintf(static::TEMPLATE_CONTENT, $filters, $actions, $table, $pagination);

        return parent::__toString();
    }
}
