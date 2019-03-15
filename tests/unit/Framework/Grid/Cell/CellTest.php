<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Cell;

use AbterPhp\Framework\Html\Component\ComponentTest;

class CellTest extends ComponentTest
{
    const DEFAULT_TEMPLATE = '<td foo="foo baz" bar="bar baz" class="td-group">Test</td>';

    const TAG   = 'td';
    const GROUP = 'group';

    /** @var Cell */
    protected $sut;

    public function setUp()
    {
        $this->sut = new Cell(static::LABEL, static::GROUP, $this->getDefaultAttributes());
    }
}
