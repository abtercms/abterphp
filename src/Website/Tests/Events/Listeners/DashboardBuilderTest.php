<?php

declare(strict_types=1);

namespace AbterPhp\Website\Tests\Events\Listeners;

use AbterPhp\Framework\Dashboard\Dashboard;
use AbterPhp\Framework\Events\DashboardReady;
use AbterPhp\Website\Events\Listeners\DashboardBuilder;
use PHPUnit\Framework\TestCase;

class DashboardBuilderTest extends TestCase
{
    /** @var DashboardBuilder - System Under Test */
    protected DashboardBuilder $sut;

    public function setUp(): void
    {
        $this->sut = new DashboardBuilder();
    }

    public function testHandle()
    {
        $dashboardMock = $this->createMock(Dashboard::class);
        $dashboardMock->expects($this->atLeastOnce())->method('offsetSet');

        $event = new DashboardReady($dashboardMock);

        $this->sut->handle($event);
    }
}
