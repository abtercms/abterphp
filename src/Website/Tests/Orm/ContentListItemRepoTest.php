<?php

declare(strict_types=1);

namespace AbterPhp\Website\Tests\Orm;

use AbterPhp\Admin\Tests\TestCase\Orm\GridRepoTestCase;
use AbterPhp\Website\Domain\Entities\ContentListItem as Entity;
use AbterPhp\Website\Orm\ContentListItemRepo as ItemRepo;

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
            'id'           => 'foo',
            'list_id'      => 'foo-list_id',
            'label'        => 'foo-label',
            'label_href'   => 'foo-label_href',
            'content'      => 'foo-content',
            'content_href' => 'foo-content_href',
            'img_src'      => 'foo-img_src',
            'img_alt'      => 'foo-img_alt',
            'img_href'     => 'foo-img_href',
            'classes'      => 'foo-classes',
        ];
        $rows[] = [
            'id'           => 'bar',
            'list_id'      => 'bar-list_id',
            'label'        => 'bar-label',
            'label_href'   => 'bar-label_href',
            'content'      => 'bar-content',
            'content_href' => 'bar-content_href',
            'img_src'      => 'bar-img_src',
            'img_alt'      => 'bar-img_alt',
            'img_href'     => 'bar-img_href',
            'classes'      => 'bar-classes',
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
            $row['list_id'],
            $row['label'],
            $row['label_href'],
            $row['content'],
            $row['content_href'],
            $row['img_src'],
            $row['img_alt'],
            $row['img_href'],
            $row['classes']
        );
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
