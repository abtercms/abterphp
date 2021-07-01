<?php

declare(strict_types=1);

namespace AbterPhp\Files\Tests\Grid\Filters;

use AbterPhp\Files\Grid\Filters\FileCategory;
use PHPUnit\Framework\TestCase;

class FileCategoryTest extends TestCase
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
        $sut = new FileCategory($intents, $attributes, $tag);

        $html = (string)$sut;

        $this->assertStringContainsString('<div class="hideable">', $html);
        $this->assertStringContainsString('filter-identifier', $html);
        $this->assertStringContainsString('filter-name', $html);
    }
}
