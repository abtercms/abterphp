<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Element;

use AbterPhp\Framework\I18n\ITranslatorMockTrait;

class InputTest extends \PHPUnit\Framework\TestCase
{
    use ITranslatorMockTrait;

    /**
     * @return array
     */
    public function renderProvider()
    {
        return [
            ['', '', '', '', [], null],
            ['', '', '', '', [], []],
        ];
    }

    /**
     * @dataProvider renderProvider
     *
     * @param string     $inputId
     * @param string     $name
     * @param string     $value
     * @param string     $tag
     * @param array      $attributes
     * @param array|null $translations
     */
    public function testRender(
        string $inputId,
        string $name,
        string $value,
        string $tag,
        array $attributes,
        ?array $translations
    ) {
        $sut = $this->createElement($inputId, $name, $value, $tag, $attributes, $translations);

        $this->markTestIncomplete();
    }

    /**
     * @param string     $inputId
     * @param string     $name
     * @param string     $value
     * @param string     $tag
     * @param array      $attributes
     * @param array|null $translations
     *
     * @return Input
     */
    private function createElement(
        string $inputId,
        string $name,
        string $value,
        string $tag,
        array $attributes,
        ?array $translations
    ): Input {
        $translatorMock = $this->getTranslatorMock($translations);

        return new Input($inputId, $name, $value, $tag, $attributes, $translatorMock);
    }
}
