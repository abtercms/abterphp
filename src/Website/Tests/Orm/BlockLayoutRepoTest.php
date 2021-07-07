<?php

declare(strict_types=1);

namespace AbterPhp\Website\Tests\Orm;

use AbterPhp\Admin\Tests\TestCase\Orm\GridRepoTestCase;
use AbterPhp\Website\Domain\Entities\BlockLayout as Entity;
use AbterPhp\Website\Orm\BlockLayoutRepo;

class BlockLayoutRepoTest extends GridRepoTestCase
{
    /** @var BlockLayoutRepo - System Under Test */
    protected BlockLayoutRepo $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new BlockLayoutRepo($this->writerMock, $this->queryBuilder);
    }

    /**
     * @return array
     */
    protected function getStubRows(): array
    {
        $rows   = [];
        $rows[] = [
            'id'         => 'foo',
            'identifier' => 'foo-identifier',
            'name'       => 'foo-name',
            'body'       => 'foo-body',
        ];
        $rows[] = [
            'id'         => 'bar',
            'identifier' => 'bar-identifier',
            'name'       => 'bar-name',
            'body'       => 'bar-body',
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
            $row['name'],
            $row['body']
        );
    }

    public function testGetByIdentifier()
    {
        $this->markTestIncomplete();
//        $identifier = 'foo-0';
//
//        $entityStub0 = new Entity('foo0', 'Foo 0', $identifier, '');
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
