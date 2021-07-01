<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Tests\Http\Views\Builders;

use AbterPhp\Admin\Http\Views\Builders\HtmlErrorBuilder;
use Opulence\Views\IView;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class HtmlErrorBuilderTest extends TestCase
{
    /** @var HtmlErrorBuilder - System Under Test */
    protected HtmlErrorBuilder $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new HtmlErrorBuilder();
    }

    public function testBuildWorks()
    {
        /** @var IView|MockObject $viewMock */
        $viewMock = $this->createMock(IView::class);

        $actualResult = $this->sut->build($viewMock);

        $this->assertSame($viewMock, $actualResult);
    }
}
