<?php

declare(strict_types=1);

namespace Integration\Framework\Template;

use AbterPhp\Framework\Template\CacheManager;
use AbterPhp\Framework\Template\SubTemplateCacheData;
use Opulence\Cache\ICacheBridge;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class CacheManagerTest extends \PHPUnit\Framework\TestCase
{
    const CACHE_KEY = 'templates_fooKey';
    const CACHED_ID = 'fooKey';

    /** @var CacheManager */
    protected $sut;

    /** @var ICacheBridge|MockObject */
    protected $client;

    public function setUp()
    {
        $this->client = $this->getMockBuilder(ICacheBridge::class)
            ->disableOriginalConstructor()
            ->setMethods(['decrement', 'delete', 'flush', 'get', 'has', 'increment', 'set'])
            ->getMock();

        $this->sut = new CacheManager($this->client);
    }

    public function testGetSubTemplateCacheDataReturnsNullIfCacheDoesNotExist()
    {
        $result = $this->sut->getSubTemplateCacheData(static::CACHED_ID);

        $this->assertNull($result);
    }

    public function testGetSubTemplateCacheDataReturnsDataIfFound()
    {
        $expectedResult = (new SubTemplateCacheData())->setSubTemplates(['block' => ['one-1', 'two-2']]);

        $this->client
            ->expects($this->any())
            ->method('get')
            ->with(static::CACHE_KEY)
            ->willReturn($expectedResult->toPayload());

        $actualResult = $this->sut->getSubTemplateCacheData(static::CACHED_ID);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testSubTemplateCacheData()
    {
        $expectedResult = (new SubTemplateCacheData())->setSubTemplates(['block' => ['one-1', 'two-2']]);

        $this->client
            ->expects($this->any())
            ->method('get')
            ->with(static::CACHE_KEY)
            ->willReturn($expectedResult->toPayload());

        $actualResult = $this->sut->getSubTemplateCacheData(static::CACHED_ID);

        $this->assertNotNull($actualResult);
        $this->assertInstanceOf(SubTemplateCacheData::class, $actualResult);
        $this->assertEquals($expectedResult, $actualResult);
    }
}
