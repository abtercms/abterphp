<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Extra;

use AbterPhp\Framework\I18n\MockTranslatorFactory;

class DefaultButtonsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return array
     */
    public function renderProvider()
    {
        return [
            'simple' => [
                '/url',
                [],
                [],
                null,
                [
                    '/\<button.*name="continue" type="submit" value="0"/Ums',
                    '/\<button.*name="continue" type="submit" value="1"/Ums',
                    '/\<a.*href="\/url"/Ums',
                ],
            ],
        ];
    }

    /**
     * @dataProvider renderProvider
     *
     * @param string      $showUrl
     * @param array       $attributes
     * @param string[]    $translations
     * @param string|null $tag
     * @param string[]    $regexps
     */
    public function testRender(
        string $showUrl,
        array $attributes,
        array $translations,
        ?string $tag,
        array $regexps
    ) {
        $sut = $this->createElement($showUrl, $attributes, $translations, $tag);

        $actualResult   = (string)$sut;
        $repeatedResult = (string)$sut;
        $this->assertSame($actualResult, $repeatedResult);

        foreach ($regexps as $piece) {
            $this->assertRegExp($piece, $actualResult);
        }
    }

    /**
     * @param string      $showUrl
     * @param array       $attributes
     * @param string[]    $translations
     * @param string|null $tag
     *
     * @return DefaultButtons
     */
    private function createElement(
        string $showUrl,
        array $attributes,
        array $translations,
        ?string $tag
    ): DefaultButtons {
        $translatorMock = MockTranslatorFactory::createSimpleTranslator($this, $translations);

        return new DefaultButtons($showUrl, $translatorMock, $attributes, $tag);
    }
}
