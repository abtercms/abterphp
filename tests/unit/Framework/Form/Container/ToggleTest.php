<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Container;

use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Extra\Help;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\Form\Label\ToggleLabelTest;
use AbterPhp\Framework\I18n\MockTranslatorFactory;
use PHPUnit\Framework\MockObject\MockObject;

class ToggleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return array
     */
    public function renderProvider()
    {
        return [
            'simple' => ['<foo>', '<bar>', '<baz>', [], null, null, '<div><bar><baz></div>'],
        ];
    }

    /**
     * @dataProvider renderProvider
     *
     * @param string        $inputOutput
     * @param string        $labelOutput
     * @param string        $helpOutput
     * @param array         $attributes
     * @param string[]|null $translations
     * @param string|null   $tag
     * @param string        $expectedResult
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
     * @param string        $inputOutput
     * @param string        $labelOutput
     * @param string        $helpOutput
     * @param array         $attributes
     * @param string[]|null $translations
     * @param string|null   $tag
     *
     * @return ToggleGroup
     */
    protected function createElement(
        string $inputOutput,
        string $labelOutput,
        string $helpOutput,
        array $attributes,
        ?array $translations,
        ?string $tag
    ): ToggleGroup {
        /** @var Input|MockObject $inputMock */
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

        $toggleGroup = new ToggleGroup($inputMock, $labelMock, $helpMock, [], $attributes, $tag);

        $toggleGroup->setTranslator($translatorMock);

        return $toggleGroup;
    }

    public function testGetAllNodesIncludesHiderBtn()
    {
        $input = new Input('foo', 'foo');
        $label = new Label('foo', 'Foo');
        $help  = new Help('help');

        $sut = new ToggleGroup($input, $label, $help);

        $actualResult = $sut->getAllNodes();

        $this->assertContains($input, $actualResult);
        $this->assertContains($label, $actualResult);
        $this->assertContains($help, $actualResult);
    }
}
