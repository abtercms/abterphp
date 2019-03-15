<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html\Collection;

use AbterPhp\Framework\Html\Component\Option;
use PHPUnit\Framework\TestCase;

class OptionsTest extends TestCase
{
    public function testToStringContainsList()
    {
        $sut = new Options();

        $sut[] = new Option('A');
        $sut[] = new Option('B');

        $this->assertContains('A', (string)$sut);
        $this->assertContains('B', (string)$sut);
    }

    public function testToStringCanWrapList()
    {
        $sut = new Options('A', ['foo' => 'baz']);

        $sut[] = new Option('B');
        $sut[] = new Option('C');

        $this->assertSame("<A foo=\"baz\"><option>B</option>\n<option>C</option></A>", (string)$sut);
    }

    public function testNext()
    {
        $this->markTestIncomplete();
    }

    public function testOffsetSet()
    {
        $this->markTestIncomplete();
    }

    public function testCount()
    {
        $this->markTestIncomplete();
    }

    public function testKey()
    {
        $this->markTestIncomplete();
    }

    public function testRewind()
    {
        $this->markTestIncomplete();
    }

    public function testCurrent()
    {
        $this->markTestIncomplete();
    }

    public function testOffsetExists()
    {
        $this->markTestIncomplete();
    }

    public function testOffsetGet()
    {
        $this->markTestIncomplete();
    }

    public function testOffsetUnset()
    {
        $this->markTestIncomplete();
    }

    public function testRender()
    {
        $this->markTestIncomplete();
    }

    public function testValid()
    {
        $this->markTestIncomplete();
    }
}
