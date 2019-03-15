<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Authorization;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Casbin\Persist\Adapter as CasbinAdapter;

class CombinedAdapterTest extends TestCase
{
    /** @var CombinedAdapter */
    protected $sut;

    /** @var CasbinAdapter|MockObject */
    protected $defaultAdapterMock;

    /** @var CacheManager|MockObject */
    protected $cacheManagerMock;

    public function setUp()
    {
        $this->defaultAdapterMock = $this->getMockBuilder(CasbinAdapter::class)
            ->setMethods(['loadPolicy', 'savePolicy', 'addPolicy', 'removePolicy', 'removeFilteredPolicy'])
            ->getMock();

        $this->cacheManagerMock = $this->getMockBuilder(CacheManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAll', 'storeAll'])
            ->getMock();

        $this->sut = new CombinedAdapter($this->defaultAdapterMock, $this->cacheManagerMock);
    }

    public function testSavePolicy()
    {
        $this->markTestIncomplete();
    }

    public function testRegisterAdapter()
    {
        $this->markTestIncomplete();
    }

    public function testLoadAdapterPolicies()
    {
        $this->markTestIncomplete();
    }

    public function testRemovePolicy()
    {
        $this->markTestIncomplete();
    }

    public function testLoadPolicy()
    {
        $this->markTestIncomplete();
    }

    public function testAddPolicy()
    {
        $this->markTestIncomplete();
    }

    public function testStoreLoadedPolicies()
    {
        $this->markTestIncomplete();
    }

    public function testLoadCachedPolicy()
    {
        $this->markTestIncomplete();
    }

    public function testRemoveFilteredPolicy()
    {
        $this->markTestIncomplete();
    }
}
