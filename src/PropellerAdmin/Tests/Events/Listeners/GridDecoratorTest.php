<?php

declare(strict_types=1);

namespace AbterPhp\PropellerAdmin\Tests\Events\Listeners;

use AbterPhp\Framework\Events\GridReady;
use AbterPhp\Framework\Grid\Grid;
use AbterPhp\PropellerAdmin\Decorator\Grid as GridDecorator;
use AbterPhp\PropellerAdmin\Decorator\General as GeneralDecorator;
use AbterPhp\PropellerAdmin\Events\Listeners\GridDecorator as Listener;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GridDecoratorTest extends TestCase
{
    /** @var Listener - System Under Test */
    protected Listener $sut;

    /** @var GridDecorator|MockObject */
    protected $gridDecoratorMock;

    /** @var GeneralDecorator|MockObject */
    protected $generalDecoratorMock;

    public function setUp(): void
    {
        $this->gridDecoratorMock = $this->getMockBuilder(GridDecorator::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['init', 'decorate'])
            ->getMock();

        $this->generalDecoratorMock = $this->getMockBuilder(GeneralDecorator::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['init', 'decorate'])
            ->getMock();

        $this->gridDecoratorMock->expects($this->any())->method('init')->willReturnSelf();
        $this->generalDecoratorMock->expects($this->any())->method('init')->willReturnSelf();

        $this->sut = new Listener($this->gridDecoratorMock, $this->generalDecoratorMock);

        parent::setUp();
    }

    public function testHandle()
    {
        /** @var Grid|MockObject $gridMock */
        $gridMock = $this->getMockBuilder(Grid::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getExtendedNodes'])
            ->getMock();

        $gridMock->expects($this->any())->method('getExtendedNodes')->willReturn([]);

        $gridReady = new GridReady($gridMock);

        $this->gridDecoratorMock->expects($this->atLeastOnce())->method('decorate');
        $this->generalDecoratorMock->expects($this->atLeastOnce())->method('decorate');

        $this->sut->handle($gridReady);
    }
}
