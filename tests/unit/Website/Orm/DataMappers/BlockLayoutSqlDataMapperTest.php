<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm\DataMapper;

use AbterPhp\Framework\Orm\DataMapper\SqlDataMapperTest;
use AbterPhp\Website\Domain\Entities\BlockLayout;
use AbterPhp\Website\Orm\DataMappers\BlockLayoutSqlDataMapper;
use Opulence\Databases\Adapters\Pdo\Connection as Connection;

class LayoutSqlDataMapperTest extends SqlDataMapperTest
{
    /** @var BlockLayoutSqlDataMapper */
    protected $sut;

    public function setUp()
    {
        $this->connection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->setMethods(['prepare', 'read', 'lastInsertId'])
            ->getMock()
        ;

        $this->sut = new BlockLayoutSqlDataMapper($this->connection, $this->connection);
    }

    public function testAdd()
    {
        $nextId     = '123';
        $identifier = 'foo';
        $body       = 'bar';

        $sql    = 'INSERT INTO block_layouts (identifier, body) VALUES (?, ?)'; // phpcs:ignore
        $values = [[$identifier, \PDO::PARAM_STR], [$body, \PDO::PARAM_STR]];

        $this->lastInsertId($nextId);
        $this->prepare($sql, $this->createWriteStatement($values));
        $entity = new BlockLayout(0, $identifier, $body);

        $this->sut->add($entity);

        $this->assertSame($nextId, $entity->getId());
    }

    public function testDelete()
    {
        $id         = 123;
        $identifier = 'foo';
        $body       = 'bar';

        $sql    = 'UPDATE block_layouts AS block_layouts SET deleted = ? WHERE (id = ?)'; // phpcs:ignore
        $values = [[1, \PDO::PARAM_INT], [$id, \PDO::PARAM_INT]];

        $this->prepare($sql, $this->createWriteStatement($values));
        $entity = new BlockLayout($id, $identifier, $body);

        $this->sut->delete($entity);
    }

    public function testGetAll()
    {
        $id         = '123';
        $identifier = 'foo';
        $body       = 'bar';

        $sql          = 'SELECT block_layouts.id, block_layouts.identifier, block_layouts.body FROM block_layouts WHERE (block_layouts.deleted = 0)'; // phpcs:ignore
        $values       = [];
        $expectedData = [['id' => $id, 'identifier' => $identifier, 'body' => $body]];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getAll();

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetById()
    {
        $id         = '123';
        $identifier = 'foo';
        $body       = 'bar';

        $sql          = 'SELECT block_layouts.id, block_layouts.identifier, block_layouts.body FROM block_layouts WHERE (block_layouts.deleted = 0) AND (block_layouts.id = :layout_id)'; // phpcs:ignore
        $values       = ['layout_id' => [$id, \PDO::PARAM_INT]];
        $expectedData = [['id' => $id, 'identifier' => $identifier, 'body' => $body]];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getById($id);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testGetByIdentifier()
    {
        $id         = '123';
        $identifier = 'foo';
        $body       = 'bar';

        $sql          = 'SELECT block_layouts.id, block_layouts.identifier, block_layouts.body FROM block_layouts WHERE (block_layouts.deleted = 0) AND (identifier = :identifier)'; // phpcs:ignore
        $values       = ['identifier' => $identifier];
        $expectedData = [['id' => $id, 'identifier' => $identifier, 'body' => $body]];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getByIdentifier($identifier);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testUpdate()
    {
        $id         = 123;
        $identifier = 'foo';
        $body       = 'bar';

        $sql    = 'UPDATE block_layouts AS block_layouts SET identifier = ?, body = ? WHERE (id = ?) AND (deleted = 0)'; // phpcs:ignore
        $values = [[$identifier, \PDO::PARAM_STR], [$body, \PDO::PARAM_STR], [$id, \PDO::PARAM_INT]];

        $this->prepare($sql, $this->createWriteStatement($values));
        $entity = new BlockLayout($id, $identifier, $body);

        $this->sut->update($entity);
    }

    /**
     * @param array      $expectedData
     * @param BlockLayout $entity
     */
    protected function assertEntity(array $expectedData, $entity)
    {
        $this->assertInstanceOf(BlockLayout::class, $entity);
        $this->assertEquals($expectedData['id'], $entity->getId());
        $this->assertSame($expectedData['identifier'], $entity->getIdentifier());
        $this->assertSame($expectedData['body'], $entity->getBody());
    }
}
