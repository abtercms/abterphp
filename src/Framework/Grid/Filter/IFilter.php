<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Filter;

use AbterPhp\Framework\Html\Component\IComponent;

interface IFilter extends IComponent
{
    /**
     * @param array $params
     *
     * @return IFilter
     */
    public function setParams(array $params): IFilter;

    /**
     * @return array
     */
    public function getWhereConditions(): array;

    /**
     * @return array
     */
    public function getQueryParams(): array;

    /**
     * @return string
     */
    public function getQueryPart(): string;
}
