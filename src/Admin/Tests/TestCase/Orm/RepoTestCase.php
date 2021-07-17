<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Tests\TestCase\Orm;

use AbterPhp\Framework\Database\PDO\Writer;
use Opulence\Orm\IEntity;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use QB\Generic\QueryBuilder\IQueryBuilder;
use QB\MySQL\QueryBuilder\QueryBuilder;

abstract class RepoTestCase extends TestCase
{
    /** @var Writer|MockObject */
    protected $writerMock;

    /** @var IQueryBuilder|MockObject */
    protected $queryBuilder;

    public function setUp(): void
    {
        parent::setUp();

        $this->writerMock = $this->createMock(Writer::class);

        $this->queryBuilder = new QueryBuilder();
    }

    /**
     * @return array<int,array<string,string>>
     */
    abstract protected function getStubRows(): array;

    /**
     * @param int $i
     *
     * @return IEntity
     */
    abstract protected function createEntityStub(int $i = 0): IEntity;

    public function testGetAll()
    {
        $rows = $this->getStubRows();

        $this->writerMock
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn($rows);

        $actualResult = $this->sut->getAll();

        $this->assertCount(2, $actualResult);
        $this->assertSame('foo', $actualResult[0]->getId());
        $this->assertSame('bar', $actualResult[1]->getId());
    }

    public function testAdd()
    {
        $entityStub0 = $this->createEntityStub();

        $this->writerMock
            ->expects($this->once())
            ->method('execute')
            ->willReturnCallback(function ($a) {
                $this->assertStringContainsString('INSERT', (string)$a);
                return true;
            });

        $this->sut->add($entityStub0);
    }

    public function testUpdate()
    {
        $entityStub0 = $this->createEntityStub();

        $this->writerMock
            ->expects($this->once())
            ->method('execute')
            ->willReturnCallback(function ($a) {
                $this->assertStringContainsString('UPDATE', (string)$a);
                return true;
            });

        $this->sut->update($entityStub0);
    }

    public function testDelete()
    {
        $entityStub0 = $this->createEntityStub();

        $this->writerMock
            ->expects($this->once())
            ->method('execute')
            ->willReturnCallback(function ($a) {
                $this->assertStringContainsString('UPDATE', (string)$a);
                return true;
            });

        $this->sut->delete($entityStub0);
    }
}
