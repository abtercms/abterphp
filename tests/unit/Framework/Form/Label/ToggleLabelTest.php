<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Label;

use AbterPhp\Framework\Helper\ArrayHelper;
use AbterPhp\Framework\Html\Component\StubAttributeFactory;
use AbterPhp\Framework\I18n\MockTranslatorFactory;

class ToggleLabelTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return array
     */
    public function renderProvider()
    {
        $defaultAttributes = ['class' => 'control-label', 'for' => 'abc'];
        $defaultStr        = ArrayHelper::toAttributes($defaultAttributes);

        $extraAttributes    = StubAttributeFactory::createAttributes();
        $combinedAttributes = ArrayHelper::mergeAttributes($defaultAttributes, $extraAttributes);
        $combinedStr        = ArrayHelper::toAttributes($combinedAttributes);

        return [
            'simple'            => ['abc', 'ABC', [], null, null, "<span$defaultStr>ABC</span>"],
            'with attributes'   => ['abc', 'ABC', $extraAttributes, [], null, "<span$combinedStr>ABC</span>"],
            'with translations' => ['abc', 'ABC', [], ['ABC' => 'CBA'], null, "<span$defaultStr>CBA</span>"],
            'with custom tag'   => ['abc', 'ABC', [], null, 'foo', "<foo$defaultStr>ABC</foo>"],
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

        $actualResult   = (string)$sut;
        $repeatedResult = (string)$sut;

        $this->assertSame($actualResult, $repeatedResult);
        $this->assertSame($expectedResult, (string)$sut);
    }

    /**
     * @param string      $inputId
     * @param string      $content
     * @param array       $attributes
     * @param array|null  $translations
     * @param string|null $tag
     *
     * @return ToggleLabel
     */
    private function createElement(
        string $inputId,
        string $content,
        array $attributes,
        ?array $translations,
        ?string $tag
    ): ToggleLabel {
        $translatorMock = MockTranslatorFactory::createSimpleTranslator($this, $translations);

        return new ToggleLabel($inputId, $content, $attributes, $translatorMock, $tag);
    }
}
