<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Container;

use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Extra\Help;
use AbterPhp\Framework\Form\Label\ToggleLabel;
use AbterPhp\Framework\I18n\ITranslatorMockTrait;
use PHPUnit\Framework\MockObject\MockObject;

class ToggleTest extends \PHPUnit\Framework\TestCase
{
    use ITranslatorMockTrait;

    public function testRender()
    {
        $sut = $this->createElement();

        $this->markTestIncomplete();
    }

    /**
     * @param string $inputOutput
     * @param string $labelOutput
     * @param string $helpOutput
     * @param string $tag
     * @param array  $attributes
     *
     * @return Toggle
     */
    protected function createElement(
        string $inputOutput = '',
        string $labelOutput = '',
        string $helpOutput = '',
        string $tag = '',
        array $attributes = [],
        ?array $translations = null
    ) {
        /** @var Input|MockObject $inputMock */
        $inputMock = $this->getMockBuilder(Input::class)
            ->disableOriginalConstructor()
            ->setMethods(['__toString'])
            ->getMock();

        /** @var ToggleLabel|MockObject $labelMock */
        $labelMock = $this->getMockBuilder(ToggleLabel::class)
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

        $translatorMock = $this->getTranslatorMock($translations);

        return new Toggle($inputMock, $labelMock, $helpMock, $tag, $attributes, $translatorMock);
    }
}
