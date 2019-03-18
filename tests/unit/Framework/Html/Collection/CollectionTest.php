<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html\Collection;

use AbterPhp\Framework\Html\Component\Component;
use AbterPhp\Framework\Html\Component\Tag;
use AbterPhp\Framework\Html\Helper\StringHelper;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    public function testToStringContainsList()
    {
        $sut = new Collection();

        $sut[] = new Component('A');
        $sut[] = new Component('B');

        $this->assertContains('A', (string)$sut);
        $this->assertContains('B', (string)$sut);
    }

    public function testToStringHandlesMixedComponents()
    {
        $sut = new Collection();

        $sut[] = new Component('A');
        $sut[] = new Tag('B');

        $this->assertContains('A', (string)$sut);
        $this->assertContains('B', (string)$sut);
    }

    /**
     * @return array
     */
    public function addingInvalidItemProvider()
    {
        return [
            [1],
            [false],
            ['aloha'],
            [[]],
            [new StringHelper()]
        ];
    }

    /**
     * @dataProvider addingInvalidItemProvider
     *
     * @expectedException \InvalidArgumentException
     *
     * @param mixed $item
     */
    public function testAddingInvalidItemFails($item)
    {
        $sut = new Collection(['foo' => 'baz'], null, 'A');

        $sut[] = $item;
    }

    public function testToStringCanWrapList()
    {
        $sut = new Collection(['foo' => 'baz'], null, 'A');

        $sut[] = new Component('B');
        $sut[] = new Component('C');

        $this->assertSame("<A foo=\"baz\">B\nC</A>", (string)$sut);
    }

    public function testCollectionIterable()
    {
        $items = [];
        $items[] = new Component('B');
        $items[] = new Component('C');

        $sut = new Collection(['foo' => 'baz'], null, 'A');

        $sut[] = $items[0];
        $sut[] = $items[1];

        foreach ($sut as $key => $component) {
            $this->assertArrayHasKey($key, $items);
            $this->assertSame($items[$key], $component);
        }
    }

    public function testCount()
    {
        $sut = new Collection(['foo' => 'baz'], null, 'A');

        $sut[] = new Component('B');
        $sut[] = new Component('C');

        $this->assertSame(2, count($sut));
    }
}
