<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Element;

use AbterPhp\Framework\Helper\ArrayHelper;
use AbterPhp\Framework\Html\Component\StubAttributeFactory;
use AbterPhp\Framework\I18n\MockTranslatorFactory;

class TextareaTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return array
     */
    public function renderProvider()
    {
        $attributes = StubAttributeFactory::createAttributes();

        $finalAttribs = ArrayHelper::mergeAttributes([Textarea::ATTRIBUTE_CLASS => 'form-control'], $attributes);
        $str          = ArrayHelper::toAttributes($finalAttribs);

        return [
            'simple'               => [
                'abc',
                'bcd',
                'val',
                [],
                null,
                null,
                '<textarea class="form-control" id="abc" rows="3" name="bcd">val</textarea>',
            ],
            'missing translations' => [
                'abc',
                'bcd',
                'val',
                [],
                [],
                null,
                '<textarea class="form-control" id="abc" rows="3" name="bcd">val</textarea>',
            ],
            'extra attributes'     => [
                'abc',
                'bcd',
                'val',
                $attributes,
                [],
                null,
                "<textarea$str id=\"abc\" rows=\"3\" name=\"bcd\">val</textarea>",
            ],
        ];
    }

    /**
     * @dataProvider renderProvider
     *
     * @param string      $inputId
     * @param string      $name
     * @param string      $value
     * @param array       $attributes
     * @param array|null  $translations
     * @param string|null $tag
     * @param string      $expectedResult
     */
    public function testRender(
        string $inputId,
        string $name,
        string $value,
        array $attributes,
        ?array $translations,
        ?string $tag,
        string $expectedResult
    ) {
        $sut = $this->createElement($inputId, $name, $value, $attributes, $translations, $tag);

        $actualResult   = (string)$sut;
        $repeatedResult = (string)$sut;

        $this->assertSame($actualResult, $repeatedResult);
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @param string      $inputId
     * @param string      $name
     * @param string      $value
     * @param array       $attributes
     * @param array|null  $translations
     * @param string|null $tag
     *
     * @return Textarea
     */
    private function createElement(
        string $inputId,
        string $name,
        string $value,
        array $attributes,
        ?array $translations,
        ?string $tag
    ): Textarea {
        $translatorMock = MockTranslatorFactory::createSimpleTranslator($this, $translations);

        return new Textarea($inputId, $name, $value, $attributes, $translatorMock, $tag);
    }
}
