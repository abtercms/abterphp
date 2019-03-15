<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html\Collection;

use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    public function testToStringContainsList()
    {
        $sut = new Collection();

        $sut[] = 'A';
        $sut[] = 'B';

        $this->assertContains('A', (string)$sut);
        $this->assertContains('B', (string)$sut);
    }

    public function testToStringCanWrapList()
    {
        $sut = new Collection('A', ['foo' => 'baz']);

        $sut[] = 'B';
        $sut[] = 'C';

        $this->assertSame("<A foo=\"baz\">B\nC</A>", (string)$sut);
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
