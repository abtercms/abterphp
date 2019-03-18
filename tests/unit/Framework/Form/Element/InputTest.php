<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Element;

use AbterPhp\Framework\Helper\ArrayHelper;
use AbterPhp\Framework\Html\Component\StubAttributeFactory;
use AbterPhp\Framework\I18n\MockTranslatorFactory;

class InputTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return array
     */
    public function renderProvider()
    {
        $attributes = StubAttributeFactory::createAttributes();

        $finalAttribs = ArrayHelper::mergeAttributes([Input::ATTRIBUTE_CLASS => 'form-control'], $attributes);
        $str          = ArrayHelper::toAttributes($finalAttribs);

        return [
            'simple'               => [
                'abc',
                'bcd',
                'val',
                [],
                null,
                null,
                '<input class="form-control" id="abc" type="text" name="bcd" value="val">',
            ],
            'missing translations' => [
                'abc',
                'bcd',
                'val',
                [],
                [],
                null,
                '<input class="form-control" id="abc" type="text" name="bcd" value="val">',
            ],
            'extra attributes'     => [
                'abc',
                'bcd',
                'val',
                $attributes,
                [],
                null,
                "<input$str id=\"abc\" type=\"text\" name=\"bcd\" value=\"val\">",
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
     * @return Input
     */
    private function createElement(
        string $inputId,
        string $name,
        string $value,
        array $attributes,
        ?array $translations,
        ?string $tag
    ): Input {
        $translatorMock = MockTranslatorFactory::createSimpleTranslator($this, $translations);

        return new Input($inputId, $name, $value, $attributes, $translatorMock, $tag);
    }
}
