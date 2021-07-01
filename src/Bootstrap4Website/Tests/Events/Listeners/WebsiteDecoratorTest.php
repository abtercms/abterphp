<?php

declare(strict_types=1);

namespace AbterPhp\Bootstrap4Website\Tests\Events\Listeners;

use AbterPhp\Bootstrap4Website\Events\Listeners\WebsiteDecorator;
use AbterPhp\Website\Events\WebsiteReady;
use Opulence\Views\IView;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class WebsiteDecoratorTest extends TestCase
{
    public const HEADER = 'foo';
    public const FOOTER = 'bar';

    /** @var WebsiteDecorator - System Under Test */
    protected WebsiteDecorator $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new WebsiteDecorator(static::HEADER, static::FOOTER);
    }

    public function testHandleWithoutSetVars()
    {
        $viewMock = $this->createMock(IView::class);
        $viewMock->expects($this->exactly(2))->method('hasVar')->willReturn(false);
        $viewMock
            ->expects($this->exactly(2))
            ->method('setVar')
            ->withConsecutive(['preHeader', static::HEADER], ['preFooter', static::FOOTER]);

        /** @var WebsiteReady|MockObject $stubWebsiteReady */
        $stubWebsiteReady = $this->createMock(WebsiteReady::class);

        $stubWebsiteReady->expects($this->any())->method('getView')->willReturn($viewMock);

        $this->sut->handle($stubWebsiteReady);
    }

    public function testHandleWithSetVars()
    {
        $setHeader = 'quix';
        $setFooter = 'baz';

        $viewMock = $this->createMock(IView::class);

        $viewMock->expects($this->exactly(2))->method('hasVar')->willReturn(true);
        $viewMock
            ->expects($this->exactly(2))
            ->method('getVar')
            ->withConsecutive(['preHeader'], ['preFooter'])
            ->willReturnOnConsecutiveCalls($setHeader, $setFooter);
        $viewMock
            ->expects($this->exactly(2))
            ->method('setVar')
            ->withConsecutive(['preHeader', static::HEADER . $setHeader], ['preFooter', static::FOOTER . $setFooter]);

        /** @var WebsiteReady|MockObject $stubWebsiteReady */
        $stubWebsiteReady = $this->createMock(WebsiteReady::class);

        $stubWebsiteReady->expects($this->any())->method('getView')->willReturn($viewMock);

        $this->sut->handle($stubWebsiteReady);
    }
}
