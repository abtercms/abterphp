<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html\Component;

use AbterPhp\Framework\I18n\ITranslatorMockTrait;
use PHPUnit\Framework\TestCase;

class ButtonTest extends TestCase
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
     * @param string $content
     * @param string $tag
     * @param array  $attributes
     * @param array  $translations
     */
    public function testRender(string $content, ?string $tag, array $attributes, array $translations = null)
    {
        $sut = $this->createElement($content, $tag, $attributes, $translations);

        $this->markTestIncomplete();
    }

    /**
     * @param string $content
     * @param string|null $tag
     * @param array $attributes
     * @param array|null $translations
     *
     * @return Button
     */
    protected function createElement(
        string $content,
        ?string $tag,
        array $attributes,
        array $translations = null
    ): Button {
        $translator = $this->getTranslatorMock($translations);

        return new Button($content, $tag, $attributes, $translator);
    }
}
