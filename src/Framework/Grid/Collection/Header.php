<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Collection;

use AbterPhp\Framework\Grid\Cell\Sortable;

class Header extends Rows
{
    const THEAD = 'thead';

    /**
     * Header constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct(static::THEAD, $attributes);
    }

    /**
     * @param string $baseUrl
     *
     * @return $this
     */
    public function setBaseUrl(string $baseUrl): Header
    {
        foreach ($this->components as $row) {
            foreach ($row->getCells() as $cell) {
                if (!($cell instanceof Sortable)) {
                    continue;
                }

                $cell->setBaseUrl($baseUrl);
            }
        }

        return $this;
    }

    /**
     * @param string $baseUrl
     *
     * @return string
     */
    public function getSortedUrl(string $baseUrl): string
    {
        $params = [];
        foreach ($this->components as $row) {
            foreach ($row->getCells() as $cell) {
                if (!($cell instanceof Sortable)) {
                    continue;
                }

                $queryParam = $cell->getQueryParam();
                if (!$queryParam) {
                    continue;
                }

                $params[] = $queryParam;
            }
        }

        return $baseUrl . implode('', $params);
    }

    /**
     * @param array $params
     *
     * @return $this
     */
    public function setParams(array $params): Header
    {
        foreach ($this->components as $row) {
            foreach ($row->getCells() as $cell) {
                if (!($cell instanceof Sortable)) {
                    continue;
                }

                $cell->setParams($params);
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getSortConditions(): array
    {
        $conditions = [];
        foreach ($this->components as $row) {
            foreach ($row->getCells() as $cell) {
                if (!($cell instanceof Sortable)) {
                    continue;
                }

                $conditions = array_merge($conditions, $cell->getSortConditions());
            }
        }

        return $conditions;
    }

    /**
     * @return array
     */
    public function getQueryParams(): array
    {
        return [];
    }
}
