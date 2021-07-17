<?php

declare(strict_types=1);

namespace AbterPhp\Website\Tests\Orm;

use AbterPhp\Admin\Tests\TestCase\Orm\GridRepoTestCase;
use AbterPhp\Website\Domain\Entities\PageCategory as Entity;
use AbterPhp\Website\Orm\PageCategoryRepo;

class PageCategoryRepoTest extends GridRepoTestCase
{
    /** @var PageCategoryRepo - System Under Test */
    protected PageCategoryRepo $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new PageCategoryRepo($this->writerMock, $this->queryBuilder);
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
            'name'       => 'foo-name',
        ];
        $rows[] = [
            'id'         => 'bar',
            'identifier' => 'bar-identifier',
            'name'       => 'bar-name',
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

        return new Entity($row['id'], $row['name'], $row['identifier']);
    }

    public function testGetByIdentifier()
    {
        $this->markTestIncomplete();
//        $identifier = 'foo-0';
//
//        $entityStub0 = new Entity('foo0', $identifier, '');
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
}
