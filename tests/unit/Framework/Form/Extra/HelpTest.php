<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Extra;

use AbterPhp\Framework\Helper\ArrayHelper;
use AbterPhp\Framework\Html\Component\StubAttributeFactory;
use AbterPhp\Framework\I18n\MockTranslatorFactory;

class HelpTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return array
     */
    public function renderProvider()
    {
        $attributes = StubAttributeFactory::createAttributes();

        $finalAttribs = ArrayHelper::mergeAttributes([Help::ATTRIBUTE_CLASS => 'help-block'], $attributes);
        $str          = ArrayHelper::toAttributes($finalAttribs);

        return [
            'simple'               => ['ABC', [], null, null, '<div class="help-block">ABC</div>'],
            'attributes'           => ['ABC', $attributes, [], null, "<div$str>ABC</div>"],
            'missing translations' => ['ABC', [], [], null, '<div class="help-block">ABC</div>'],
            'found translations'   => ['ABC', [], ['ABC' => 'CBA'], null, '<div class="help-block">CBA</div>'],
        ];
    }

    /**
     * @dataProvider renderProvider
     *
     * @param string      $content
     * @param array       $attributes
     * @param array|null  $translations
     * @param string|null $tag
     * @param string      $expectedResult
     */
    public function testRender(
        string $content,
        array $attributes,
        ?array $translations,
        ?string $tag,
        string $expectedResult
    ) {
        $sut = $this->createElement($content, $attributes, $translations, $tag);

        $actualResult   = (string)$sut;
        $repeatedResult = (string)$sut;

        $this->assertSame($actualResult, $repeatedResult);
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @param string      $content
     * @param array       $attributes
     * @param array|null  $translations
     * @param string|null $tag
     *
     * @return Help
     */
    private function createElement(
        string $content,
        array $attributes,
        ?array $translations,
        ?string $tag
    ): Help {
        $translatorMock = MockTranslatorFactory::createSimpleTranslator($this, $translations);

        return new Help($content, $attributes, $translatorMock, $tag);
    }
}
