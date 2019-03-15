<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Element;

use AbterPhp\Framework\I18n\ITranslatorMockTrait;

class SelectTest extends \PHPUnit\Framework\TestCase
{
    use ITranslatorMockTrait;

    /**
     * @return array
     */
    public function renderProvider()
    {
        return [
            ['', '', '', false, [], '', [], null],
            ['', '', '', false, [], '', [], []],
            ['', '', '', true, [], '', [], []],
        ];
    }

    /**
     * @dataProvider renderProvider
     *
     * @param string $inputId
     * @param string $name
     * @param string $value
     * @param bool   $multiple
     * @param array   $options
     * @param string $tag
     * @param array  $attributes
     * @param array|null $translations
     */
    public function testRender(
        string $inputId,
        string $name,
        string $value,
        bool $multiple,
        array $options,
        string $tag,
        array $attributes,
        ?array $translations
    ) {
        $sut = $this->createElement($inputId, $name, $value, $multiple, $options, $tag, $attributes, $translations);

        $this->markTestIncomplete();
    }

    /**
     * @param string $inputId
     * @param string $name
     * @param string $value
     * @param bool   $multiple
     * @param array  $options
     * @param string $tag
     * @param array  $attributes
     * @param array|null $translations
     *
     * @return Select
     */
    protected function createElement(
        string $inputId,
        string $name,
        string $value,
        bool $multiple,
        array $options,
        string $tag,
        array $attributes,
        ?array $translations
    ): Select {
        $translatorMock = $this->getTranslatorMock($translations);

        $select = new Select($inputId, $name, $value, $multiple, $tag, $attributes, $translatorMock);

        foreach ($options as $option) {
            $select[] = $option;
        }

        return $select;
    }
}
