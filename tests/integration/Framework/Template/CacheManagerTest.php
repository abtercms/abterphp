<?php

declare(strict_types=1);

namespace Integration\Framework\Template;

use AbterPhp\Framework\Template\CacheData;
use AbterPhp\Framework\Template\CacheManager;
use Opulence\Cache\ICacheBridge;
use PHPUnit\Framework\MockObject;

class CacheManagerTest extends \PHPUnit\Framework\TestCase
{
    const CACHE_KEY = 'templates_fooKey';
    const CACHED_ID = 'fooKey';

    /** @var CacheManager */
    protected $sut;

    /** @var ICacheBridge|MockObject */
    protected $client;

    public function setUp(): void
    {
        $this->client = $this->getMockBuilder(ICacheBridge::class)
            ->disableOriginalConstructor()
            ->setMethods(['decrement', 'delete', 'flush', 'get', 'has', 'increment', 'set'])
            ->getMock();

        $this->sut = new CacheManager($this->client);
    }

    public function testGetSubTemplateCacheDataReturnsNullIfCacheDoesNotExist()
    {
        $result = $this->sut->getCacheData(static::CACHED_ID);

        $this->assertNull($result);
    }

    public function testGetSubTemplateCacheDataReturnsDataIfFound()
    {
        $expectedResult = (new CacheData())->setSubTemplates(['block' => ['one-1', 'two-2']]);

        $payload = json_encode(
            [
                CacheData::PAYLOAD_KEY_DATE         => $expectedResult->getDate(),
                CacheData::PAYLOAD_KEY_SUBTEMPLATES => $expectedResult->getSubTemplates(),
            ]
        );

        $this->client
            ->expects($this->any())
            ->method('get')
            ->with(static::CACHE_KEY)
            ->willReturn($payload);

        $actualResult = $this->sut->getCacheData(static::CACHED_ID);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testSubTemplateCacheData()
    {
        $expectedResult = (new CacheData())->setSubTemplates(['block' => ['one-1', 'two-2']]);

        $payload = json_encode(
            [
                CacheData::PAYLOAD_KEY_DATE         => $expectedResult->getDate(),
                CacheData::PAYLOAD_KEY_SUBTEMPLATES => $expectedResult->getSubTemplates(),
            ]
        );

        $this->client
            ->expects($this->any())
            ->method('get')
            ->with(static::CACHE_KEY)
            ->willReturn($payload);

        $actualResult = $this->sut->getCacheData(static::CACHED_ID);

        $this->assertNotNull($actualResult);
        $this->assertInstanceOf(CacheData::class, $actualResult);
        $this->assertEquals($expectedResult, $actualResult);
    }
}
