<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Component;

use AbterPhp\Framework\Helper\ArrayHelper;
use AbterPhp\Framework\Html\Component\StubAttributeFactory;
use AbterPhp\Framework\I18n\MockTranslatorFactory;

class OptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return array
     */
    public function renderProvider()
    {
        $attribs = StubAttributeFactory::createAttributes();
        $str     = ArrayHelper::toAttributes($attribs);

        return [
            'simple'           => ['abc', 'ABC', false, [], null, null, "<option value=\"abc\">ABC</option>"],
            'attributes'       => ['abc', 'ABC', false, $attribs, null, null, "<option value=\"abc\"$str>ABC</option>"],
            'w/o translations' => ['abc', 'ABC', false, [], [], null, "<option value=\"abc\">ABC</option>"],
            'custom tag'       => ['abc', 'ABC', false, [], null, 'foo', "<foo value=\"abc\">ABC</foo>"],
            'w translations'   => ['abc', 'ABC', false, [], ['ABC' => '+'], null, "<option value=\"abc\">+</option>"],
        ];
    }

    /**
     * @dataProvider renderProvider
     *
     * @param string      $value
     * @param string      $content
     * @param array       $attributes
     * @param array|null  $translations
     * @param string|null $tag
     * @param string      $expectedResult
     */
    public function testRender(
        string $value,
        string $content,
        bool $isSelected,
        array $attributes,
        ?array $translations,
        ?string $tag,
        string $expectedResult
    ) {
        $sut = $this->createElement($value, $content, $isSelected, $attributes, $translations, $tag);

        $actualResult1 = (string)$sut;
        $actualResult2 = (string)$sut;

        $this->assertSame($expectedResult, $actualResult1);
        $this->assertSame($expectedResult, $actualResult2);
    }

    /**
     * @param string      $value
     * @param string      $content
     * @param bool        $isSelected
     * @param array       $attributes
     * @param array|null  $translations
     * @param string|null $tag
     *
     * @return Option
     */
    protected function createElement(
        string $value,
        string $content,
        bool $isSelected,
        array $attributes,
        ?array $translations,
        ?string $tag
    ): Option {
        $translatorMock = MockTranslatorFactory::createSimpleTranslator($this, $translations);

        return new Option($value, $content, $isSelected, $attributes, $translatorMock, $tag);
    }
}
