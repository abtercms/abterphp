<?php

declare(strict_types=1);

namespace AbterPhp\Website\Tests\Orm;

use AbterPhp\Admin\Tests\TestCase\Orm\GridRepoTestCase;
use AbterPhp\Admin\Tests\TestCase\Orm\RepoTestCase;
use AbterPhp\Website\Domain\Entities\ContentListItem as Entity;
use AbterPhp\Website\Orm\ContentListItemRepo as ItemRepo;
use Opulence\Orm\DataMappers\IDataMapper;
use Opulence\Orm\IEntityRegistry;
use PHPUnit\Framework\MockObject\MockObject;

class ContentListItemRepoTest extends GridRepoTestCase
{
    /** @var ItemRepo - System Under Test */
    protected ItemRepo $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new ItemRepo($this->writerMock, $this->queryBuilder);
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

    public function testGetByListId()
    {
        $this->markTestIncomplete();
//        $listId = 'bar0';
//
//        $entityStub0 = new Entity('foo0', $listId, '', '', '', '', '', '', '', '');
//        $entityStub1 = new Entity('foo1', $listId, '', '', '', '', '', '', '', '');
//        $entities    = [$entityStub0, $entityStub1];
//
//        $entityRegistry = $this->createEntityRegistryStub(null);
//
//        $this->dataMapperMock->expects($this->once())->method('getByListId')->willReturn($entities);
//
//        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);
//
//        $actualResult = $this->sut->getByListId($listId);
//
//        $this->assertSame($entities, $actualResult);
    }

    public function testGetByListIds()
    {
        $this->markTestIncomplete();
//        $listId0 = 'bar0';
//        $listId1 = 'bar1';
//
//        $entityStub0 = new Entity('foo0', $listId0, '', '', '', '', '', '', '', '');
//        $entityStub1 = new Entity('foo1', $listId1, '', '', '', '', '', '', '', '');
//        $entities    = [$entityStub0, $entityStub1];
//
//        $entityRegistry = $this->createEntityRegistryStub(null);
//
//        $this->dataMapperMock->expects($this->once())->method('getByListIds')->willReturn($entities);
//
//        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);
//
//        $actualResult = $this->sut->getByListIds([$listId0, $listId1]);
//
//        $this->assertSame($entities, $actualResult);
    }
}
