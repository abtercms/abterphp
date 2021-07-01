<?php

declare(strict_types=1);

namespace AbterPhp\Website\Tests\Grid\Filters;

use AbterPhp\Website\Grid\Filters\Block;
use PHPUnit\Framework\TestCase;

class BlockTest extends TestCase
{
    /**
     * @return array
     */
    public function filterProvider(): array
    {
        return [
            [[], [], null],
        ];
    }

    /**
     * @dataProvider filterProvider
     *
     * @param string[]    $intents
     * @param array       $attributes
     * @param string|null $tag
     */
    public function testFilter(array $intents, array $attributes, ?string $tag)
    {
        $sut = new Block($intents, $attributes, $tag);

        $html = (string)$sut;

        $this->assertStringContainsString('<div class="hideable">', $html);
        $this->assertStringContainsString('filter-identifier', $html);
        $this->assertStringContainsString('filter-title', $html);
    }
}
