<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Cell;

use AbterPhp\Framework\Helper\ArrayHelper;
use AbterPhp\Framework\Html\Component\ComponentTest;
use AbterPhp\Framework\Html\Component\StubAttributeFactory;
use AbterPhp\Framework\I18n\MockTranslatorFactory;

class CellTest extends ComponentTest
{
    /**
     * @return array
     */
    public function renderProvider()
    {
        $defaultAttributes = [Cell::ATTRIBUTE_CLASS => 'td-a'];

        $extraAttributes = StubAttributeFactory::createAttributes();

        $combinedAttributes = ArrayHelper::mergeAttributes($extraAttributes, $defaultAttributes);

        $str = ArrayHelper::toAttributes($combinedAttributes);

        return [
            'simple'               => ['ABC', 'a', [], null, null, "<td class=\"td-a\">ABC</td>"],
            'with attributes'      => ['ABC', 'a', $extraAttributes, null, null, "<td$str>ABC</td>"],
            'missing translations' => ['ABC', 'a', [], [], null, "<td class=\"td-a\">ABC</td>"],
            'custom tag'           => ['ABC', 'a', [], null, 'mytd', "<mytd class=\"mytd-a\">ABC</mytd>"],
            'with translations'    => ['ABC', 'a', [], ['ABC' => 'CBA'], null, "<td class=\"td-a\">CBA</td>"],
        ];
    }

    /**
     * @dataProvider renderProvider
     *
     * @param string      $content
     * @param string      $group
     * @param array       $attributes
     * @param array|null  $translations
     * @param string|null $tag
     * @param string      $expectedResult
     */
    public function testRender(
        string $content,
        string $group,
        array $attributes,
        ?array $translations,
        ?string $tag,
        string $expectedResult
    ) {
        $sut = $this->createElement($content, $group, $attributes, $translations, $tag);

        $actualResult1 = (string)$sut;
        $actualResult2 = (string)$sut;

        $this->assertSame($expectedResult, $actualResult1);
        $this->assertSame($expectedResult, $actualResult2);
    }

    /**
     * @param string      $content
     * @param string      $group
     * @param array       $attributes
     * @param array|null  $translations
     * @param string|null $tag
     *
     * @return Cell
     */
    protected function createElement(
        string $content,
        string $group,
        array $attributes,
        ?array $translations,
        ?string $tag
    ): Cell {
        $translatorMock = MockTranslatorFactory::createSimpleTranslator($this, $translations);

        return new Cell($content, $group, $attributes, $translatorMock, $tag);
    }
}
