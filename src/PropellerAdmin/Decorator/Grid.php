<?php

declare(strict_types=1);

namespace AbterPhp\PropellerAdmin\Decorator;

use AbterPhp\Framework\Decorator\Decorator;
use AbterPhp\Framework\Decorator\Rule;
use AbterPhp\Framework\Grid\Cell\Sortable;
use AbterPhp\Framework\Grid\Filter\Filter;
use AbterPhp\Framework\Grid\Pagination\Pagination;
use AbterPhp\Framework\Html\Attribute;

class Grid extends Decorator
{
    public const FILTER_INPUT_CLASS   = 'form-control';

    public const PAGINATION_TEMPLATE = '<div class="gp-numbers col-md-6">%1$s</div><div class="gp-options col-md-6">%2$s%3$s</div>'; // nolint

    public const HELP_BLOCK_CLASS = 'help-block';

    protected const FILTER_WRAPPER_CLASS = 'form-group pmd-textfield pmd-textfield-floating-label';
    protected const FILTER_LABEL_CLASS   = 'control-label';

    protected const FILTER_ATTR_DATA_TOGGLE    = 'data-toggle';
    protected const FILTER_ATTR_DATA_PLACEMENT = 'data-placement';
    protected const FILTER_DATA_TOGGLE_TOOLTIP = 'tooltip';
    protected const FILTER_DATA_PLACEMENT_LEFT = 'left';

    protected const PAGINATION_CLASS    = 'grid-pagination row';

    protected const CARET_CLASS = 'caret';
    protected const CARET_ACTIVE_CLASS = 'caret-active';
    protected const CARET_DOWN_CLASS = 'caret-down';
    protected const CARET_UP_CLASS = 'caret-up';

    /**
     * @return $this
     */
    public function init(): Decorator
    {
        $this->rules[] = new Rule([], Filter::class, [static::FILTER_INPUT_CLASS], [], [$this, 'buildFilter']);
        $this->rules[] = new Rule([], Pagination::class, [static::PAGINATION_CLASS], [], [$this, 'decoratePagination']);
        $this->rules[] = new Rule([Filter::INTENT_HELP_BLOCK], null, [static::HELP_BLOCK_CLASS]);
        $this->rules[] = new Rule([Sortable::BTN_INTENT_SHOARTING], null, [static::CARET_CLASS]);
        $this->rules[] = new Rule([Sortable::BTN_INTENT_CARET_ACTIVE], null, [static::CARET_ACTIVE_CLASS]);
        $this->rules[] = new Rule([Sortable::BTN_INTENT_CARET_DOWN], null, [static::CARET_DOWN_CLASS]);
        $this->rules[] = new Rule([Sortable::BTN_INTENT_CARET_UP], null, [static::CARET_UP_CLASS]);

        return $this;
    }

    /**
     * @param Filter $input
     */
    public function buildFilter(Filter $input)
    {
        $input->setAttribute(new Attribute(static::FILTER_ATTR_DATA_TOGGLE, static::FILTER_DATA_TOGGLE_TOOLTIP));
        $input->setAttribute(new Attribute(static::FILTER_ATTR_DATA_PLACEMENT, static::FILTER_DATA_PLACEMENT_LEFT));

        $input->getWrapper()->appendToClass(static::FILTER_WRAPPER_CLASS);
        $input->getLabel()->appendToClass(static::FILTER_LABEL_CLASS);
    }

    /**
     * @param Pagination $pagination
     */
    public function decoratePagination(Pagination $pagination)
    {
        $pagination->setTemplate(static::PAGINATION_TEMPLATE);
    }
}
