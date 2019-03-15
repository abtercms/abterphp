<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Factory\Table;

use AbterPhp\Framework\Grid\Cell\Cell;
use AbterPhp\Framework\Grid\Cell\Sortable;
use AbterPhp\Framework\Grid\Collection\Cells;
use AbterPhp\Framework\Grid\Collection\Header as Component;
use AbterPhp\Framework\Grid\Row\Row;
use AbterPhp\Framework\I18n\ITranslator;

class Header
{
    const ACTIONS_CONTENT = 'framework:actions';
    const ACTIONS_GROUP   = 'actions';

    /** @var ITranslator */
    protected $translator;

    /** @var array */
    protected $headers = [];

    /** @var array */
    protected $inputNames = [];

    /** @var array */
    protected $fieldNames = [];

    /**
     * Header constructor.
     *
     * @param ITranslator $translator
     */
    public function __construct(ITranslator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param array  $headers
     * @param bool   $hasActions
     * @param array  $params
     * @param string $baseUrl
     *
     * @return Header
     */
    public function create(array $headers, bool $hasActions, array $params, string $baseUrl): Component
    {
        $headers = $this->headers ?: $headers;

        $header = new Component();

        $cells = new Cells();
        foreach ($headers as $group => $content) {
            $cells[] = $this->createCell($content, $group);
        }

        if ($hasActions) {
            $cells[] = new Cell(static::ACTIONS_CONTENT, static::ACTIONS_GROUP, [], Cell::HEAD, $this->translator);
        }

        $header[] = new Row($cells);

        $header->setParams($params)->setBaseUrl($baseUrl);

        return $header;
    }

    /**
     * @param string $content
     * @param string $group
     *
     * @return Cell
     */
    protected function createCell(string $content, string $group): Cell
    {
        if (!array_key_exists($group, $this->inputNames)) {
            return new Cell($content, $group, [], Cell::HEAD, $this->translator);
        }

        $inputName = $this->inputNames[$group];
        $fieldName = $this->fieldNames[$group];

        return new Sortable($content, $group, $inputName, $fieldName, [], $this->translator);
    }
}
