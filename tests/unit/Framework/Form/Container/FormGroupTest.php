<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Container;

use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Extra\Help;
use AbterPhp\Framework\Form\Element\IElement;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\I18n\MockTranslatorFactory;
use PHPUnit\Framework\MockObject\MockObject;

class FormGroupTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return array
     */
    public function renderProvider()
    {
        return [
            'simple' => [
                '<foo>',
                '<bar>',
                '<baz>',
                [],
                null,
                null,
                '<div class="form-group"><bar><foo><baz></div>',
            ],
        ];
    }

    /**
     * @dataProvider renderProvider
     *
     * @param string      $inputOutput
     * @param string      $labelOutput
     * @param string      $helpOutput
     * @param array       $attributes
     * @param array|null  $translations
     * @param string|null $tag
     * @param string      $expectedResult
     */
    public function testRender(
        string $inputOutput,
        string $labelOutput,
        string $helpOutput,
        array $attributes,
        ?array $translations,
        ?string $tag,
        string $expectedResult
    ) {
        $sut = $this->createElement($inputOutput, $labelOutput, $helpOutput, $attributes, $translations, $tag);

        $actualResult   = (string)$sut;
        $repeatedResult = (string)$sut;

        $this->assertSame($actualResult, $repeatedResult);
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @param string     $inputOutput
     * @param string     $labelOutput
     * @param string     $helpOutput
     * @param string     $tag
     * @param array      $attributes
     * @param array|null $translations
     *
     * @return FormGroup
     */
    private function createElement(
        string $inputOutput,
        string $labelOutput,
        string $helpOutput,
        array $attributes,
        ?array $translations,
        ?string $tag
    ) {
        /** @var IElement|MockObject $inputMock */
        $inputMock = $this->getMockBuilder(Input::class)
            ->disableOriginalConstructor()
            ->setMethods(['__toString'])
            ->getMock();

        /** @var Label|MockObject $labelMock */
        $labelMock = $this->getMockBuilder(Label::class)
            ->disableOriginalConstructor()
            ->setMethods(['__toString'])
            ->getMock();

        /** @var Help|MockObject $helpMock */
        $helpMock = $this->getMockBuilder(Help::class)
            ->setMethods(['__toString'])
            ->getMock();

        $inputMock->expects($this->any())->method('__toString')->willReturn($inputOutput);
        $labelMock->expects($this->any())->method('__toString')->willReturn($labelOutput);
        $helpMock->expects($this->any())->method('__toString')->willReturn($helpOutput);

        $translatorMock = MockTranslatorFactory::createSimpleTranslator($this, $translations);

        return new FormGroup($inputMock, $labelMock, $helpMock, $attributes, $translatorMock, $tag);
    }
}
