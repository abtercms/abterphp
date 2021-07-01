<?php

declare(strict_types=1);

namespace AbterPhp\Files\Tests\Authorization;

use AbterPhp\Files\Authorization\FileCategoryProvider;
use AbterPhp\Files\Databases\Queries\FileCategoryAuthLoader as AuthLoader;
use Casbin\Model\Model;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FileCategoryProviderTest extends TestCase
{
    /** @var FileCategoryProvider */
    protected FileCategoryProvider $sut;

    /** @var AuthLoader|MockObject */
    protected $authLoaderMock;

    public function setUp(): void
    {
        $this->authLoaderMock = $this->createMock(AuthLoader::class);

        $this->sut = new FileCategoryProvider($this->authLoaderMock);
    }

    public function testSavePolicyDoesNotThrowException()
    {
        $modelStub = $this->createMock(Model::class);

        $this->sut->savePolicy($modelStub);

        // Test is there to verify that the function works, but it's expected to be empty.
        $this->assertTrue(true);
    }

    public function testAddPolicyDoesNotThrowException()
    {
        $this->sut->addPolicy('foo', 'bar', []);

        // Test is there to verify that the function works, but it's expected to be empty.
        $this->assertTrue(true);
    }

    public function testRemovePolicyDoesNotThrowException()
    {
        $this->sut->removePolicy('foo', 'bar', []);

        // Test is there to verify that the function works, but it's expected to be empty.
        $this->assertTrue(true);
    }

    public function testRemoveFilteredPolicyDoesNotThrowException()
    {
        $this->sut->removeFilteredPolicy('foo', 'bar', 0);

        // Test is there to verify that the function works, but it's expected to be empty.
        $this->assertTrue(true);
    }
}
