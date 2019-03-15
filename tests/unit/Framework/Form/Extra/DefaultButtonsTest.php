<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Extra;

use AbterPhp\Framework\I18n\ITranslatorMockTrait;

class DefaultButtonsTest extends \PHPUnit\Framework\TestCase
{
    use ITranslatorMockTrait;

    /**
     * @return array
     */
    public function renderProvider()
    {
        return [
            ['', '', [], []],
        ];
    }

    /**
     * @dataProvider renderProvider
     *
     * @param string     $showUrl
     * @param string     $tag
     * @param array      $attributes
     * @param array|null $translations
     */
    public function testRender(string $showUrl, string $tag, array $attributes, ?array $translations)
    {
        $sut = $this->createElement($showUrl, $tag, $attributes, $translations);

        $this->markTestIncomplete();
    }

    /**
     * @param string     $showUrl
     * @param string     $tag
     * @param array      $attributes
     * @param array|null $translations
     *
     * @return DefaultButtons
     */
    private function createElement(
        string $showUrl,
        string $tag,
        array $attributes,
        ?array $translations
    ): DefaultButtons {
        $translatorMock = $this->getTranslatorMock($translations);

        return new DefaultButtons($showUrl, $tag, $attributes, $translatorMock);
    }
}
