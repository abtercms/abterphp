<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Label;

use AbterPhp\Framework\I18n\MockTranslatorFactory;

class LabelTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return array
     */
    public function renderProvider()
    {
        return [
            'simple'            => [
                'a',
                'ABC',
                [],
                null,
                null,
                '<label class="control-label" for="a">ABC</label>',
            ],
            'with attributes'   => [
                'a',
                'ABC',
                ['foo' => 'bar', 'class' => 'baz'],
                null,
                null,
                '<label class="control-label baz" for="a" foo="bar">ABC</label>',
            ],
            'with translations' => [
                'a',
                'ABC',
                [],
                ['ABC' => 'CBA'],
                null,
                '<label class="control-label" for="a">CBA</label>',
            ],
            'custom tag'        => [
                'a',
                'ABC',
                [],
                [],
                'foo',
                '<foo class="control-label" for="a">ABC</foo>',
            ],
        ];
    }

    /**
     * @dataProvider renderProvider
     *
     * @param string      $inputId
     * @param string      $content
     * @param array       $attributes
     * @param array|null  $translations
     * @param string|null $tag
     * @param string      $expectedResult
     */
    public function testRender(
        string $inputId,
        string $content,
        array $attributes,
        ?array $translations,
        ?string $tag,
        string $expectedResult
    ) {
        $sut = $this->createElement($inputId, $content, $attributes, $translations, $tag);

        $this->assertSame($expectedResult, (string)$sut);
    }

    /**
     * @param string      $inputId
     * @param string      $content
     * @param array       $attributes
     * @param array|null  $translations
     * @param string|null $tag
     *
     * @return Label
     */
    private function createElement(
        string $inputId,
        string $content,
        array $attributes,
        ?array $translations,
        ?string $tag
    ): Label {
        $translatorMock = MockTranslatorFactory::createSimpleTranslator($this, $translations);

        return new Label($inputId, $content, $attributes, $translatorMock, $tag);
    }
}
