<?php

declare(strict_types=1);

namespace AbterPhp\PropellerAdmin\Decorator;

use AbterPhp\Framework\Decorator\Decorator;
use AbterPhp\Framework\Decorator\Rule;
use AbterPhp\Framework\Grid\Filter\Filter;
use AbterPhp\Framework\Grid\Pagination\Pagination;

class Grid extends Decorator
{
    const FILTER_WRAPPER_CLASS = 'form-group pmd-textfield pmd-textfield-floating-label';
    const FILTER_LABEL_CLASS   = 'control-label';
    const FILTER_INPUT_CLASS   = 'form-control';

    const FILTER_ATTR_DATA_TOGGLE    = 'data-toggle';
    const FILTER_ATTR_DATA_PLACEMENT = 'data-placement';
    const FILTER_DATA_TOGGLE_TOOLTIP = 'tooltip';
    const FILTER_DATA_PLACEMENT_LEFT = 'left';

    const PAGINATION_CLASS    = 'grid-pagination row';
    const PAGINATION_TEMPLATE = '<div class="gp-numbers col-md-6">%1$s</div><div class="gp-options col-md-6">%2$s%3$s</div>'; // nolint

    const HELP_BLOCK_CLASS = 'help-block';

    /**
     * @return $this
     */
    public function init(): Decorator
    {
        $this->rules[] = new Rule([], Filter::class, [static::FILTER_INPUT_CLASS], [], [$this, 'buildFilter']);
        $this->rules[] = new Rule([], Pagination::class, [static::PAGINATION_CLASS], [], [$this, 'decoratePagination']);
        $this->rules[] = new Rule([Filter::INTENT_HELP_BLOCK], null, [static::HELP_BLOCK_CLASS]);

        return $this;
    }

    /**
     * @param Filter $input
     */
    public function buildFilter(Filter $input)
    {
        $input->setAttribute(static::FILTER_ATTR_DATA_TOGGLE, static::FILTER_DATA_TOGGLE_TOOLTIP);
        $input->setAttribute(static::FILTER_ATTR_DATA_PLACEMENT, static::FILTER_DATA_PLACEMENT_LEFT);

        $input->getWrapper()->appendToClass(static::FILTER_WRAPPER_CLASS);
        $input->getLabel()->appendToClass(static::FILTER_LABEL_CLASS);
    }

    /**
     * @param Filter $input
     */
    public function decoratePagination(Pagination $pagination)
    {
        $pagination->setTemplate(static::PAGINATION_TEMPLATE);
    }
}
