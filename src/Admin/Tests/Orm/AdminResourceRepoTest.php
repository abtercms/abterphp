<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Tests\Orm;

use AbterPhp\Admin\Domain\Entities\AdminResource as Entity;
use AbterPhp\Admin\Orm\AdminResourceRepo;
use AbterPhp\Admin\Tests\TestCase\Orm\RepoTestCase;

class AdminResourceRepoTest extends RepoTestCase
{
    /** @var AdminResourceRepo - System Under Test */
    protected AdminResourceRepo $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new AdminResourceRepo($this->writerMock, $this->queryBuilder);
    }

    /**
     * @return array
     */
    protected function getStubRows(): array
    {
        $rows   = [];
        $rows[] = [
            'id'              => 'foo',
            'identifier'      => 'foo-identifier',
        ];
        $rows[] = [
            'id'              => 'bar',
            'identifier'      => 'bar-identifier',
        ];

        return $rows;
    }

    /**
     * @param int $i
     *
     * @return Entity
     */
    protected function createEntityStub(int $i = 0): Entity
    {
        $rows = $this->getStubRows();
        $row  = $rows[$i];

        return new Entity(
            $row['id'],
            $row['identifier']
        );
    }

    public function testGetByIdentifier()
    {
        $this->markTestIncomplete();
//        $identifier = 'foo-0';
//        $entityStub = new Entity('foo0', $identifier);
//
//        $entityRegistry = $this->createEntityRegistryStub(null);
//
//        $this->dataMapperMock->expects($this->once())->method('getByIdentifier')->willReturn($entityStub);
//
//        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);
//
//        $actualResult = $this->sut->getByIdentifier($identifier);
//
//        $this->assertSame($entityStub, $actualResult);
    }

    public function testGetByUserId()
    {
        $this->markTestIncomplete();
//        $userId = 'user0';
//
//        $entityStub0 = new Entity('foo0', 'foo-0');
//        $entityStub1 = new Entity('foo1', 'foo-1');
//        $entities    = [$entityStub0, $entityStub1];
//
//        $entityRegistry = $this->createEntityRegistryStub(null);
//
//        $this->dataMapperMock->expects($this->once())->method('getByUserId')->willReturn($entities);
//
//        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);
//
//        $actualResult = $this->sut->getByUserId($userId);
//
//        $this->assertSame($entities, $actualResult);
    }
}
