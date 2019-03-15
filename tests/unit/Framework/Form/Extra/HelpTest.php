<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Extra;

use AbterPhp\Framework\I18n\ITranslatorMockTrait;

class HelpTest extends \PHPUnit\Framework\TestCase
{
    use ITranslatorMockTrait;

    /**
     * @return array
     */
    public function renderProvider()
    {
        return [
            ['', '', [], null],
            ['', '', [], []],
        ];
    }

    /**
     * @dataProvider renderProvider
     *
     * @param string     $content
     * @param string     $tag
     * @param array      $attributes
     * @param array|null $translations
     */
    public function testRender(string $content, string $tag, array $attributes, ?array $translations)
    {
        $sut = $this->createElement($content, $tag, $attributes, $translations);

        $this->markTestIncomplete();
    }

    /**
     * @param string     $content
     * @param string     $tag
     * @param array      $attributes
     * @param array|null $translations
     *
     * @return Help
     */
    private function createElement(
        string $content,
        string $tag,
        array $attributes,
        ?array $translations
    ): Help {
        $translatorMock = $this->getTranslatorMock($translations);

        return new Help($content, $tag, $attributes, $translatorMock);
    }
}
