<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Tests\Orm;

use AbterPhp\Admin\Tests\TestCase\Orm\GridRepoTestCase;
use AbterPhp\Contact\Domain\Entities\Form as Entity;
use AbterPhp\Contact\Orm\FormRepo;

class FormRepoTest extends GridRepoTestCase
{
    /** @var FormRepo - System Under Test */
    protected FormRepo $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new FormRepo($this->writerMock, $this->queryBuilder);
    }

    /**
     * @return array<int,array<string,string>>
     */
    protected function getStubRows(): array
    {
        $rows   = [];
        $rows[] = [
            'id'              => 'foo',
            'name'            => 'foo-name',
            'identifier'      => 'foo-identifier',
            'to_name'         => 'foo-to_name',
            'to_email'        => 'foo-to_email',
            'success_url'     => 'foo-success_url',
            'failure_url'     => 'foo-failure_url',
            'max_body_length' => '2182',
        ];
        $rows[] = [
            'id'              => 'bar',
            'name'            => 'bar-name',
            'identifier'      => 'bar-identifier',
            'to_name'         => 'bar-to_name',
            'to_email'        => 'bar-to_email',
            'success_url'     => 'bar-success_url',
            'failure_url'     => 'bar-failure_url',
            'max_body_length' => '4382',
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
            $row['to_name'],
            $row['to_email'],
            $row['success_url'],
            $row['failure_url'],
            (int)$row['max_body_length']
        );
    }

    public function testGetByIdentifier()
    {
        $this->markTestIncomplete();
//        $identifier = 'foo-0';
//
//        $entityStub0 = new Entity('foo0', 'foo-0', '', '', '', '', '', 0);
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
