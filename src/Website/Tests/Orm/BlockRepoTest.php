<?php

declare(strict_types=1);

namespace AbterPhp\Website\Tests\Orm;

use AbterPhp\Admin\Tests\TestCase\Orm\GridRepoTestCase;
use AbterPhp\Website\Domain\Entities\Block as Entity;
use AbterPhp\Website\Orm\BlockRepo;

class BlockRepoTest extends GridRepoTestCase
{
    /** @var BlockRepo - System Under Test */
    protected BlockRepo $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new BlockRepo($this->writerMock, $this->queryBuilder);
    }

    /**
     * @return array<int,array<string,string>>
     */
    protected function getStubRows(): array
    {
        $rows   = [];
        $rows[] = [
            'id'         => 'foo',
            'identifier' => 'foo-identifier',
            'title'      => 'foo-title',
            'body'       => 'foo-body',
            'layout'     => 'foo-layout',
        ];
        $rows[] = [
            'id'         => 'bar',
            'identifier' => 'bar-identifier',
            'title'      => 'bar-title',
            'body'       => 'bar-body',
            'layout'     => 'bar-layout',
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

        return new Entity($row['id'], $row['identifier'], $row['title'], $row['body'], $row['layout']);
    }

    public function testGetByIdentifier()
    {
        $this->markTestIncomplete();
//        $identifier = 'foo-0';
//
//        $entityStub0 = new Entity('foo0', 'foo-0', '', '', '');
//
//        $entityRegistry = $this->createEntityRegistryStub(null);
//
//        $this->dataMapperMock->expects($this->once())->method('getByIdentifier')->willReturn($entityStub0);
//
//        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);
//
//        $actualResult = $this->sut->getByIdentifier($identifier);
//
//        $this->assertSame($entityStub0, $actualResult);
    }

    public function testGetWithLayoutByIdentifiers()
    {
        $this->markTestIncomplete();
//        $identifier0 = 'foo-0';
//        $identifier1 = 'foo-0';
//
//        $entityStub0 = new Entity('foo0', $identifier0, '', '', '');
//        $entityStub1 = new Entity('foo1', $identifier1, '', '', '');
//        $entities    = [$entityStub0, $entityStub1];
//
//        $entityRegistry = $this->createEntityRegistryStub(null);
//
//        $this->dataMapperMock->expects($this->once())->method('getWithLayoutByIdentifiers')->willReturn($entities);
//
//        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);
//
//        $actualResult = $this->sut->getWithLayoutByIdentifiers([$identifier0, $identifier1]);
//
//        $this->assertSame($entities, $actualResult);
    }
}
