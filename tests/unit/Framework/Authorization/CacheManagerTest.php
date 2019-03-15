<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Authorization;

use Opulence\Cache\ICacheBridge;
use PHPUnit\Framework\TestCase;

class CacheManagerTest extends TestCase
{
    /** @var CacheManager */
    protected $sut;

    /** @var ICacheBridge */
    protected $clientMock;

    public function setUp()
    {
        $this->clientMock = $this->getMockBuilder(ICacheBridge::class)
            ->setMethods(['decrement', 'delete', 'flush', 'get', 'has', 'increment', 'set'])
            ->getMock();

        $this->sut = new CacheManager($this->clientMock);
    }

    public function testClearAll()
    {
        $this->markTestIncomplete();
    }

    public function testStoreAll()
    {
        $this->markTestIncomplete();
    }

    public function testGetAll()
    {
        $this->markTestIncomplete();
    }
}
