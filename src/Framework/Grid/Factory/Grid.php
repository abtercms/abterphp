<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Factory;

use AbterPhp\Framework\Grid\Collection\Actions;
use AbterPhp\Framework\Grid\Collection\Filters;
use AbterPhp\Framework\Grid\Grid as Component;
use AbterPhp\Framework\Grid\Pagination\IPagination;
use AbterPhp\Framework\Grid\Table\Table;
use AbterPhp\Framework\I18n\ITranslator;

class Grid
{
    const ATTRIBUTE_CLASS = 'class';

    /** @var ITranslator */
    protected $translator;

    /** @var array */
    protected $attributes = [
        self::ATTRIBUTE_CLASS => 'grid',
    ];

    /**
     * Grid constructor.
     *
     * @param ITranslator $translator
     */
    public function __construct(ITranslator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param Table        $table
     * @param IPagination  $pagination
     * @param Filters      $filters
     * @param Actions|null $actions
     *
     * @return Component
     */
    public function create(Table $table, IPagination $pagination, Filters $filters, ?Actions $actions): Component
    {
        return new Component($table, $pagination, $filters, $actions, $this->attributes, $this->translator);
    }
}
