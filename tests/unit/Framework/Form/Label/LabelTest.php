<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Label;

use AbterPhp\Framework\I18n\ITranslatorMockTrait;

class LabelTest extends \PHPUnit\Framework\TestCase
{
    use ITranslatorMockTrait;

    /**
     * @return array
     */
    public function renderProvider()
    {
        return [
            ['', '', '', [], null],
            ['', '', '', [], []],
        ];
    }

    /**
     * @dataProvider renderProvider
     *
     * @param string     $inputId
     * @param string     $content
     * @param string     $tag
     * @param array      $attributes
     * @param array|null $translations
     */
    public function testRender(string $inputId, string $content, string $tag, array $attributes, ?array $translations)
    {
        $sut = $this->createElement($inputId, $content, $tag, $attributes, $translations);

        $this->markTestIncomplete();
    }

    /**
     * @param string     $inputId
     * @param string     $content
     * @param string     $tag
     * @param array      $attributes
     * @param array|null $translations
     *
     * @return Label
     */
    private function createElement(
        string $inputId,
        string $content,
        string $tag,
        array $attributes,
        ?array $translations
    ): Label {
        $translatorMock = $this->getTranslatorMock($translations);
        return new Label($inputId, $content, $tag, $attributes);
    }
}
