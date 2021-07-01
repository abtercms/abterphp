<?php

declare(strict_types=1);

namespace AbterPhp\Bootstrap4Website\Tests\Events\Listeners;

use AbterPhp\Bootstrap4Website\Decorator\Form;
use AbterPhp\Bootstrap4Website\Events\Listeners\FormDecorator;
use AbterPhp\Framework\Events\FormReady;
use AbterPhp\Framework\Form\IForm;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FormDecoratorTest extends TestCase
{
    /** @var FormDecorator - System Under Test */
    protected FormDecorator $sut;

    /** @var MockObject|Form */
    protected $formDecoratorMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->formDecoratorMock = $this->createMock(Form::class);
        $this->formDecoratorMock->expects($this->any())->method('init')->willReturnSelf();

        $this->sut = new FormDecorator($this->formDecoratorMock);
    }

    public function testHandle()
    {
        $nodes = ['foo', 'bar'];

        $formStub = $this->createMock(IForm::class);
        $formStub
            ->expects($this->any())
            ->method('getExtendedNodes')
            ->willReturn($nodes);

        $event = new FormReady($formStub);

        $this->formDecoratorMock
            ->expects($this->once())
            ->method('decorate')
            ->with([$formStub, 'foo', 'bar']);

        $this->sut->handle($event);
    }
}
