<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid;

use AbterPhp\Framework\Grid\Collection\Filters;
use AbterPhp\Framework\Grid\Table\Table;
use PHPUnit\Framework\MockObject\MockObject;

class GridTest extends \PHPUnit\Framework\TestCase
{
    public function testToStringContainsTable()
    {
        /** @var Table|MockObject $table */
        $table = $this->getMockBuilder(Table::class)
            ->disableOriginalConstructor()
            ->setMethods(['__toString'])
            ->getMock()
        ;

        $table->expects($this->any())->method('__toString')->willReturn('ABC');

        $sut = new Grid($table);

        $this->assertContains('ABC', (string)$sut);
    }

    public function testToStringContainsFilters()
    {
        /** @var Table|MockObject $table */
        $table = $this->getMockBuilder(Table::class)
            ->disableOriginalConstructor()
            ->setMethods(['__toString'])
            ->getMock()
        ;

        $table->expects($this->any())->method('__toString')->willReturn('ABC');

        $sut = new Grid($table);

        $this->assertContains('ABC', (string)$sut);
    }

    public function testToStringContainsActions()
    {
        /** @var Table|MockObject $table */
        $table = $this->getMockBuilder(Table::class)
            ->disableOriginalConstructor()
            ->setMethods(['__toString'])
            ->getMock()
        ;

        $table->expects($this->any())->method('__toString')->willReturn('ABC');

        $sut = new Grid($table);

        $this->assertContains('ABC', (string)$sut);
    }

    public function testToStringCanWrapContentInForm()
    {
        /** @var Table|MockObject $table */
        $table = $this->getMockBuilder(Table::class)
            ->disableOriginalConstructor()
            ->setMethods(['__toString'])
            ->getMock()
        ;

        /** @var Filters|MockObject $filters */
        $filters = $this->getMockBuilder(Filters::class)
            ->disableOriginalConstructor()
            ->setMethods(['__toString'])
            ->getMock()
        ;

        $table->expects($this->any())->method('__toString')->willReturn('A');
        $filters->expects($this->any())->method('__toString')->willReturn('B');

        $sut = new Grid($table, null, $filters);

        $this->assertContains(Grid::TAG_GRID, (string)$sut);
    }
}
