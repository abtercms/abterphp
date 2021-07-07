<?php

declare(strict_types=1);

namespace AbterPhp\Website\Tests\Orm;

use AbterPhp\Admin\Tests\TestCase\Orm\RepoTestCase;
use AbterPhp\Website\Domain\Entities\PageLayout as Entity;
use AbterPhp\Website\Orm\DataMappers\PageLayoutSqlDataMapper;
use AbterPhp\Website\Orm\PageLayoutRepo;
use Opulence\Orm\DataMappers\IDataMapper;
use Opulence\Orm\IEntityRegistry;
use PHPUnit\Framework\MockObject\MockObject;

class PageLayoutRepoTest extends RepoTestCase
{
    /** @var PageLayoutRepo - System Under Test */
    protected PageLayoutRepo $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new PageLayoutRepo($this->writerMock, $this->queryBuilder);
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

    /**
     * @return PageLayoutSqlDataMapper|MockObject
     */
    protected function createDataMapperMock(): IDataMapper
    {
        /** @var PageLayoutSqlDataMapper|MockObject $mock */
        return $this->createMock(PageLayoutSqlDataMapper::class);
    }

    public function testGetAll()
    {
        $entityStub0 = new Entity('foo0', 'Foo 0', 'foo-0', '', '', null);
        $entityStub1 = new Entity('foo1', 'Foo 1', 'foo-1', '', '', null);
        $entities    = [$entityStub0, $entityStub1];

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->dataMapperMock->expects($this->once())->method('getAll')->willReturn($entities);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $actualResult = $this->sut->getAll();

        $this->assertSame($entities, $actualResult);
    }

    public function testGetByIdFromCache()
    {
        $entityStub = new Entity('foo0', 'Foo 0', 'foo-0', '', '', null);

        $entityRegistry = $this->createEntityRegistryStub($entityStub);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $this->dataMapperMock->expects($this->never())->method('getById');

        $id = 'foo';

        $actualResult = $this->sut->getById($id);

        $this->assertSame($entityStub, $actualResult);
    }

    public function testGetByIdFromDataMapper()
    {
        $entityStub = new Entity('foo0', 'Foo 0', 'foo-0', '', '', null);

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $this->dataMapperMock->expects($this->once())->method('getById')->willReturn($entityStub);

        $id = 'foo';

        $actualResult = $this->sut->getById($id);

        $this->assertSame($entityStub, $actualResult);
    }

    public function testAdd()
    {
        $entityStub = new Entity('foo0', 'Foo 0', 'foo-0', '', '', null);

        $this->unitOfWorkMock->expects($this->once())->method('scheduleForInsertion')->with($entityStub);

        $this->sut->add($entityStub);
    }

    public function testDelete()
    {
        $entityStub = new Entity('foo0', 'Foo 0', 'foo-0', '', '', null);

        $this->unitOfWorkMock->expects($this->once())->method('scheduleForDeletion')->with($entityStub);

        $this->sut->delete($entityStub);
    }

    public function testGetPage()
    {
        $entityStub0 = new Entity('foo0', 'Foo 0', 'foo-0', '', '', null);
        $entityStub1 = new Entity('foo1', 'Foo 1', 'foo-1', '', '', null);
        $entities    = [$entityStub0, $entityStub1];

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->dataMapperMock->expects($this->once())->method('getPage')->willReturn($entities);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $actualResult = $this->sut->getPage(0, 10, [], [], []);

        $this->assertSame($entities, $actualResult);
    }

    public function testGetByIdentifier()
    {
        $identifier = 'foo-0';

        $entityStub0 = new Entity('foo0', 'Foo 0', 'foo-0', '', '', null);

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->dataMapperMock->expects($this->once())->method('getByIdentifier')->willReturn($entityStub0);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $actualResult = $this->sut->getByIdentifier($identifier);

        $this->assertSame($entityStub0, $actualResult);
    }

    /**
     * @param Entity|null $entity
     *
     * @return MockObject
     */
    protected function createEntityRegistryStub(?Entity $entity): MockObject
    {
        $entityRegistry = $this->createMock(IEntityRegistry::class);
        $entityRegistry->expects($this->any())->method('registerEntity');
        $entityRegistry->expects($this->any())->method('getEntity')->willReturn($entity);

        return $entityRegistry;
    }
}
