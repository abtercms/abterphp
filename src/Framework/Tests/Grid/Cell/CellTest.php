<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Tests\Grid\Cell;

use AbterPhp\Framework\Grid\Cell\Cell;
use AbterPhp\Framework\Html\Helper\Attributes;
use AbterPhp\Framework\Html\INode;
use AbterPhp\Framework\Tests\TestDouble\Html\Component\StubAttributeFactory;
use AbterPhp\Framework\Tests\TestDouble\I18n\MockTranslatorFactory;
use PHPUnit\Framework\TestCase;

class CellTest extends TestCase
{
    protected const CONTENT = 'foo';
    protected const GROUP   = 'bar';

    /**
     * @return array[]
     */
    public function renderProvider(): array
    {
        $attribs = StubAttributeFactory::createAttributes();
        $str     = Attributes::toString($attribs);

        return [
            'simple'               => ['ABC', 'a', [], null, null, "<td>ABC</td>"],
            'with attributes'      => ['ABC', 'a', $attribs, null, null, "<td$str>ABC</td>"],
            'missing translations' => ['ABC', 'a', [], [], null, "<td>ABC</td>"],
            'custom tag'           => ['ABC', 'a', [], null, 'mytd', "<mytd>ABC</mytd>"],
            'with translations'    => ['ABC', 'a', [], ['ABC' => 'CBA'], null, "<td>CBA</td>"],
        ];
    }

    /**
     * @dataProvider renderProvider
     *
     * @param INode[]|INode|string|null $content
     * @param string                    $group
     * @param array<string,mixed>       $attributes
     * @param array|null                $translations
     * @param string|null               $tag
     * @param string                    $expectedResult
     */
    public function testRender(
        $content,
        string $group,
        array $attributes,
        ?array $translations,
        ?string $tag,
        string $expectedResult
    ): void {
        $sut = $this->createCell($content, $group, $attributes, $translations, $tag);

        $actualResult1 = (string)$sut;
        $actualResult2 = (string)$sut;

        $this->assertSame($expectedResult, $actualResult1);
        $this->assertSame($expectedResult, $actualResult2);
    }

    public function testGetGroup()
    {
        $expectedResult = 'foo';

        $sut = $this->createCell(null, $expectedResult);

        $group = $sut->getGroup();

        $this->assertSame($expectedResult, $group);
    }

    /**
     * @param INode[]|INode|string|null $content
     * @param string|null               $group
     * @param array<string,mixed>       $attributes
     * @param array|null                $translations
     * @param string|null               $tag
     *
     * @return Cell
     */
    protected function createCell(
        $content = null,
        string $group = null,
        array $attributes = [],
        ?array $translations = null,
        ?string $tag = null
    ): Cell {
        $group ??= static::GROUP;

        $cell = new Cell($content, $group, [], $attributes, $tag);

        $cell->setTranslator(MockTranslatorFactory::createSimpleTranslator($this, $translations));

        return $cell;
    }
}
