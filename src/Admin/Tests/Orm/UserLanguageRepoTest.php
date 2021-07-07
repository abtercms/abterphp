<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Tests\Orm;

use AbterPhp\Admin\Domain\Entities\UserLanguage as Entity;
use AbterPhp\Admin\Orm\UserLanguageRepo;
use AbterPhp\Admin\Tests\TestCase\Orm\GridRepoTestCase;

class UserLanguageRepoTest extends GridRepoTestCase
{
    /** @var UserLanguageRepo - System Under Test */
    protected UserLanguageRepo $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new UserLanguageRepo($this->writerMock, $this->queryBuilder);
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

        return new Entity(
            $row['id'],
            $row['identifier'],
            $row['name']
        );
    }

    public function testGetByIdentifier()
    {
        $this->markTestIncomplete();
//        $identifier = 'foo-0';
//        $entityStub = new Entity('foo0', $identifier, 'Foo 0');
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
//        $entityStub0 = new Entity('foo0', 'foo-0', 'Foo 0');
//        $entityStub1 = new Entity('foo1', 'foo-1', 'Foo 1');
//        $entities    = [$entityStub0, $entityStub1];
//
//        $entityRegistry = $this->createEntityRegistryStub(null);
//
//        $this->dataMapperMock->expects($this->once())->method('getPage')->willReturn($entities);
//
//        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);
//
//        $actualResult = $this->sut->getPage(0, 10, [], [], []);
//
//        $this->assertSame($entities, $actualResult);
    }
}
