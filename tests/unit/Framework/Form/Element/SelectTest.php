<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Element;

use AbterPhp\Framework\Form\Component\Option;
use AbterPhp\Framework\Helper\ArrayHelper;
use AbterPhp\Framework\Html\Component\StubAttributeFactory;
use AbterPhp\Framework\I18n\MockTranslatorFactory;

class SelectTest extends \PHPUnit\Framework\TestCase
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
                false,
                [],
                [],
                null,
                null,
                '<select class="form-control" id="abc" name="bcd"></select>',
            ],
            'missing translations' => [
                'abc',
                'bcd',
                'val',
                false,
                [],
                [],
                [],
                null,
                '<select class="form-control" id="abc" name="bcd"></select>',
            ],
            'extra attributes'     => [
                'abc',
                'bcd',
                'val',
                false,
                [],
                $attributes,
                [],
                null,
                "<select$str id=\"abc\" name=\"bcd\"></select>",
            ],
            'options'              => [
                'abc',
                'bcd',
                'val',
                false,
                ['bde' => 'BDE', 'cef' => 'CEF'],
                $attributes,
                [],
                null,
                "<select$str id=\"abc\" name=\"bcd\"><option value=\"bde\">BDE</option>\n<option value=\"cef\">CEF</option></select>", // nolint
            ],
            'option selected'      => [
                'abc',
                'bcd',
                'cef',
                false,
                ['bde' => 'BDE', 'cef' => 'CEF'],
                $attributes,
                [],
                null,
                "<select$str id=\"abc\" name=\"bcd\"><option value=\"bde\">BDE</option>\n<option value=\"cef\" selected>CEF</option></select>", // nolint
            ],
        ];
    }

    /**
     * @dataProvider renderProvider
     *
     * @param string      $inputId
     * @param string      $name
     * @param string      $value
     * @param bool        $multiple
     * @param string[]    $options
     * @param array       $attributes
     * @param array|null  $translations
     * @param string|null $tag
     * @param string      $expectedResult
     */
    public function testRender(
        string $inputId,
        string $name,
        string $value,
        bool $multiple,
        array $options,
        array $attributes,
        ?array $translations,
        ?string $tag,
        string $expectedResult
    ) {
        $sut = $this->createElement($inputId, $name, $value, $multiple, $options, $attributes, $translations, $tag);

        $actualResult   = (string)$sut;
        $repeatedResult = (string)$sut;

        $this->assertSame($actualResult, $repeatedResult);
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @param string      $inputId
     * @param string      $name
     * @param string      $value
     * @param bool        $multiple
     * @param string[]    $options
     * @param array       $attributes
     * @param array|null  $translations
     * @param string|null $tag
     *
     * @return Select
     */
    protected function createElement(
        string $inputId,
        string $name,
        string $value,
        bool $multiple,
        array $options,
        array $attributes,
        ?array $translations,
        ?string $tag
    ): Select {
        $translatorMock = MockTranslatorFactory::createSimpleTranslator($this, $translations);

        $select = new Select($inputId, $name, $multiple, $attributes, $translatorMock, $tag);

        foreach ($options as $k => $v) {
            $select[] = new Option($k, $v, $value == $k);
        }

        return $select;
    }
}
