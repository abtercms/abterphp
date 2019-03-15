<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Template;

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

    public function testStoreDocument()
    {
        $this->markTestIncomplete();
    }

    public function testGetSubTemplateCacheData()
    {
        $this->markTestIncomplete();
    }

    public function testGetDocument()
    {
        $this->markTestIncomplete();
    }

    public function testClearAll()
    {
        $this->markTestIncomplete();
    }

    public function testStoreSubTemplateCacheData()
    {
        $this->markTestIncomplete();
    }
}
