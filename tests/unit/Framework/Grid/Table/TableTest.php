<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Table;

use AbterPhp\Framework\Grid\Collection\Rows;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class TableTest extends TestCase
{
    /** @var Table */
    protected $sut;

    /** @var Rows|MockObject */
    protected $body;

    /** @var Rows|MockObject */
    protected $header;

    public function setUp()
    {
        $methods = ['__toString', 'setIndentation'];

        $this->body = $this->getMockBuilder(Rows::class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();

        $this->header = $this->getMockBuilder(Rows::class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();

        $this->sut = new Table($this->body, $this->header);
    }

    public function testToStringContainsHeaders()
    {
        $this->body->expects($this->any())->method('__toString')->willReturn('A');
        $this->header->expects($this->any())->method('__toString')->willReturn('B');

        $this->assertContains((string)$this->header, (string)$this->sut);
    }

    public function testToStringContainsRows()
    {
        $this->body->expects($this->any())->method('__toString')->willReturn('A');
        $this->header->expects($this->any())->method('__toString')->willReturn('B');

        $this->assertContains((string)$this->body, (string)$this->sut);
    }
}
