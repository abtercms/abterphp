<?php

declare(strict_types=1);

namespace AbterPhp\Website\Tests\Orm;

use AbterPhp\Admin\Tests\TestCase\Orm\GridRepoTestCase;
use AbterPhp\Website\Domain\Entities\ContentList as Entity;
use AbterPhp\Website\Orm\ContentListRepo;

class ContentListRepoTest extends GridRepoTestCase
{
    /** @var ContentListRepo - System Under Test */
    protected ContentListRepo $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new ContentListRepo($this->writerMock, $this->queryBuilder);
    }

    /**
     * @return array<int,array<string,string>>
     */
    protected function getStubRows(): array
    {
        $rows   = [];
        $rows[] = [
            'id'               => 'foo',
            'name'             => 'foo-name',
            'identifier'       => 'foo-identifier',
            'classes'          => 'foo-classes',
            'protected'        => 'foo-protected',
            'with_links'       => 'foo-with_links',
            'with_label_links' => 'foo-with_label_links',
            'with_html'        => 'foo-with_html',
            'with_images'      => 'foo-with_images',
            'with_classes'     => 'foo-with_classes',
        ];
        $rows[] = [
            'id'               => 'bar',
            'name'             => 'bar-name',
            'identifier'       => 'bar-identifier',
            'classes'          => 'bar-classes',
            'protected'        => 'bar-protected',
            'with_links'       => 'bar-with_links',
            'with_label_links' => 'bar-with_label_links',
            'with_html'        => 'bar-with_html',
            'with_images'      => 'bar-with_images',
            'with_classes'     => 'bar-with_classes',
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
            $row['name'],
            $row['identifier'],
            $row['classes'],
            (bool)$row['protected'],
            (bool)$row['with_links'],
            (bool)$row['with_label_links'],
            (bool)$row['with_html'],
            (bool)$row['with_images'],
            (bool)$row['with_classes']
        );
    }

    public function testGetIdentifier()
    {
        $this->markTestIncomplete();
//        $identifier = 'foo-0';
//
//        $entityStub0 = new Entity('foo0', 'foo-0', '', '', false, false, false, false, false, false);
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
//        $identifier1 = 'foo-1';
//
//        $entityStub0 = new Entity('foo0', $identifier0, '', '', false, false, false, false, false, false);
//        $entityStub1 = new Entity('foo1', $identifier1, '', '', false, false, false, false, false, false);
//        $entities    = [$entityStub0, $entityStub1];
//
//        $entityRegistry = $this->createEntityRegistryStub(null);
//
//        $this->dataMapperMock->expects($this->once())->method('getByIdentifiers')->willReturn($entities);
//
//        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);
//
//        $actualResult = $this->sut->getByIdentifiers([$identifier0, $identifier1]);
//
//        $this->assertSame($entities, $actualResult);
    }
}
