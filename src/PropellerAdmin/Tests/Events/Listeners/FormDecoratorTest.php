<?php

declare(strict_types=1);

namespace AbterPhp\PropellerAdmin\Tests\Events\Listeners;

use AbterPhp\Framework\Events\FormReady;
use AbterPhp\Framework\Form\Form;
use AbterPhp\PropellerAdmin\Decorator\Form as FormDecorator;
use AbterPhp\PropellerAdmin\Decorator\General as GeneralDecorator;
use AbterPhp\PropellerAdmin\Events\Listeners\FormDecorator as Listener;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FormDecoratorTest extends TestCase
{
    /** @var Listener - System Under Test */
    protected $sut;

    /** @var FormDecorator|MockObject */
    protected $formDecoratorMock;

    /** @var GeneralDecorator|MockObject */
    protected $generalDecoratorMock;

    public function setUp(): void
    {
        $this->formDecoratorMock = $this->getMockBuilder(FormDecorator::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['init', 'decorate'])
            ->getMock();

        $this->generalDecoratorMock = $this->getMockBuilder(GeneralDecorator::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['init', 'decorate'])
            ->getMock();

        $this->formDecoratorMock->expects($this->any())->method('init')->willReturnSelf();
        $this->generalDecoratorMock->expects($this->any())->method('init')->willReturnSelf();

        $this->sut = new Listener($this->formDecoratorMock, $this->generalDecoratorMock);

        parent::setUp();
    }

    public function testHandle()
    {
        /** @var Form|MockObject $formMock */
        $formMock = $this->getMockBuilder(Form::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getExtendedNodes'])
            ->getMock();

        $formMock->expects($this->any())->method('getExtendedNodes')->willReturn([]);

        $adminReady = new FormReady($formMock);

        $this->formDecoratorMock->expects($this->atLeastOnce())->method('decorate');
        $this->generalDecoratorMock->expects($this->atLeastOnce())->method('decorate');

        $this->sut->handle($adminReady);
    }
}
