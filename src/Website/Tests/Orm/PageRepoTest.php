<?php

declare(strict_types=1);

namespace AbterPhp\Website\Tests\Orm;

use AbterPhp\Admin\Tests\TestCase\Orm\GridRepoTestCase;
use AbterPhp\Website\Domain\Entities\Page as Entity;
use AbterPhp\Website\Orm\PageRepo;

class PageRepoTest extends GridRepoTestCase
{
    /** @var PageRepo - System Under Test */
    protected PageRepo $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new PageRepo($this->writerMock, $this->queryBuilder);
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
            'classes'    => 'foo-classes',
            'lede'       => 'foo-lede',
            'body'       => 'foo-body',
            'is_draft'   => '1',
        ];
        $rows[] = [
            'id'         => 'bar',
            'identifier' => 'bar-identifier',
            'title'      => 'bar-title',
            'classes'    => 'bar-classes',
            'lede'       => 'bar-lede',
            'body'       => 'bar-body',
            'is_draft'   => '0',
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
            $row['identifier'],
            $row['title'],
            $row['classes'],
            $row['lede'],
            $row['body'],
            (bool)$row['is_draft']
        );
    }

    public function testGetByIdentifier()
    {
        $this->markTestIncomplete();
//        $identifier = 'foo-0';
//
//        $entityStub0 = new Entity('foo0', 'foo-0', '', '', '', '', false);
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

    public function testGetWithLayout()
    {
        $this->markTestIncomplete();
//        $identifier = 'foo-0';
//
//        $entityStub0 = new Entity('foo0', 'foo-0', '', '', '', '', false);
//
//        $entityRegistry = $this->createEntityRegistryStub(null);
//
//        $this->dataMapperMock->expects($this->once())->method('getWithLayout')->willReturn($entityStub0);
//
//        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);
//
//        $actualResult = $this->sut->getWithLayout($identifier);
//
//        $this->assertSame($entityStub0, $actualResult);
    }

    public function testGetByCategoryIdentifiers()
    {
        $this->markTestIncomplete();
//        $identifier0 = 'foo-0';
//        $identifier1 = 'foo-1';
//
//        $entityStub0 = new Entity('foo0', $identifier0, '', '', '', '', false);
//        $entityStub1 = new Entity('foo1', $identifier1, '', '', '', '', false);
//        $entities    = [$entityStub0, $entityStub1];
//
//        $entityRegistry = $this->createEntityRegistryStub(null);
//
//        $this->dataMapperMock->expects($this->once())->method('getByCategoryIdentifiers')->willReturn($entities);
//
//        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);
//
//        $actualResult = $this->sut->getByCategoryIdentifiers([$identifier0, $identifier1]);
//
//        $this->assertSame($entities, $actualResult);
    }
}
