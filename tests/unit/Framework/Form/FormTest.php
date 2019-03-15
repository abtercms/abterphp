<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form;

use AbterPhp\Framework\I18n\ITranslatorMockTrait;

class FormTest extends \PHPUnit\Framework\TestCase
{
    use ITranslatorMockTrait;

    /**
     * @return array
     */
    public function renderProvider()
    {
        return [
            ['', '', '', [], []],
        ];
    }

    /**
     * @dataProvider renderProvider
     *
     * @param string     $action
     * @param string     $method
     * @param string     $tag
     * @param array      $attributes
     * @param array|null $translations
     */
    public function testRender(string $action, string $method, string $tag, array $attributes, ?array $translations)
    {
        $sut = $this->createElement($action, $method, $tag, $attributes, $translations);

        $this->markTestIncomplete();
    }

    /**
     * @param string     $action
     * @param string     $method
     * @param string     $tag
     * @param array      $attributes
     * @param array|null $translations
     *
     * @return Form
     */
    private function createElement(
        string $action,
        string $method,
        string $tag,
        array $attributes,
        ?array $translations
    ): Form {
        $translatorMock = $this->getTranslatorMock($translations);

        return new Form($action, $method, $tag, $attributes, $translatorMock);
    }
}
