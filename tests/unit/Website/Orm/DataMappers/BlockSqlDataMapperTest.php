<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm\DataMapper;

use AbterPhp\Framework\Orm\DataMapper\SqlDataMapperTest;
use AbterPhp\Website\Domain\Entities\Block;
use AbterPhp\Website\Orm\DataMappers\BlockSqlDataMapper;
use Opulence\Databases\Adapters\Pdo\Connection as Connection;

class BlockSqlDataMapperTest extends SqlDataMapperTest
{
    /** @var BlockSqlDataMapper */
    protected $sut;

    public function setUp()
    {
        $this->connection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->setMethods(['prepare', 'read', 'lastInsertId'])
            ->getMock()
        ;

        $this->sut = new BlockSqlDataMapper($this->connection, $this->connection);
    }

    public function testAddWithoutLayoutId()
    {
        $nextId     = '123';
        $identifier = 'foo';
        $title      = 'bar';
        $body       = 'baz';
        $layout     = 'qux';
        $layoutId   = null;

        $sql    = 'INSERT INTO blocks (identifier, title, body, layout, layout_id) VALUES (?, ?, ?, ?, ?)'; // phpcs:ignore
        $values = [
            [$identifier, \PDO::PARAM_STR],
            [$title, \PDO::PARAM_STR],
            [$body, \PDO::PARAM_STR],
            [$layout, \PDO::PARAM_STR],
            [$layoutId, \PDO::PARAM_NULL],
        ];

        $this->lastInsertId($nextId);
        $this->prepare($sql, $this->createWriteStatement($values));
        $entity = new Block(0, $identifier, $title, $body, $layout, $layoutId);

        $this->sut->add($entity);

        $this->assertSame($nextId, $entity->getId());
    }

    public function testAddWithLayoutId()
    {
        $nextId     = '123';
        $identifier = 'foo';
        $title      = 'bar';
        $body       = 'baz';
        $layout     = '';
        $layoutId   = 66;

        $sql    = 'INSERT INTO blocks (identifier, title, body, layout, layout_id) VALUES (?, ?, ?, ?, ?)'; // phpcs:ignore
        $values = [
            [$identifier, \PDO::PARAM_STR],
            [$title, \PDO::PARAM_STR],
            [$body, \PDO::PARAM_STR],
            [$layout, \PDO::PARAM_STR],
            [$layoutId, \PDO::PARAM_INT],
        ];

        $this->lastInsertId($nextId);
        $this->prepare($sql, $this->createWriteStatement($values));
        $entity = new Block(0, $identifier, $title, $body, $layout, $layoutId);

        $this->sut->add($entity);

        $this->assertSame($nextId, $entity->getId());
    }

    public function testDelete()
    {
        $id         = '123';
        $identifier = 'foo';
        $title      = 'bar';
        $body       = 'baz';
        $layout     = 'qux';
        $layoutId   = null;

        $sql    = 'UPDATE blocks AS blocks SET deleted = ? WHERE (id = ?)'; // phpcs:ignore
        $values = [[1, \PDO::PARAM_INT], [$id, \PDO::PARAM_INT]];

        $this->prepare($sql, $this->createWriteStatement($values));
        $entity = new Block($id, $identifier, $title, $body, $layout, $layoutId);

        $this->sut->delete($entity);
    }

    public function testGetAll()
    {
        $id         = '123';
        $identifier = 'foo';
        $title      = 'bar';
        $body       = 'baz';
        $layout     = 'qux';
        $layoutId   = null;

        $sql          = 'SELECT blocks.id, blocks.identifier, blocks.title, blocks.body, blocks.layout_id, blocks.layout FROM blocks WHERE (blocks.deleted = 0)'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'         => $id,
                'identifier' => $identifier,
                'title'      => $title,
                'body'       => $body,
                'layout'     => $layout,
                'layout_id'  => $layoutId,
            ],
        ];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getAll();

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetById()
    {
        $id         = '123';
        $identifier = 'foo';
        $title      = 'bar';
        $body       = 'baz';
        $layout     = 'qux';
        $layoutId   = null;

        $sql          = 'SELECT blocks.id, blocks.identifier, blocks.title, blocks.body, blocks.layout_id, blocks.layout FROM blocks WHERE (blocks.deleted = 0) AND (blocks.id = :block_id)'; // phpcs:ignore
        $values       = ['block_id' => [$id, \PDO::PARAM_INT]];
        $expectedData = [
            [
                'id'         => $id,
                'identifier' => $identifier,
                'title'      => $title,
                'body'       => $body,
                'layout'     => $layout,
                'layout_id'  => $layoutId,
            ],
        ];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getById($id);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testGetByIdentifier()
    {
        $id         = '123';
        $identifier = 'foo';
        $title      = 'bar';
        $body       = 'baz';
        $layout     = 'qux';
        $layoutId   = null;

        $sql          = 'SELECT blocks.id, blocks.identifier, blocks.title, blocks.body, blocks.layout_id, blocks.layout FROM blocks WHERE (blocks.deleted = 0) AND (blocks.identifier = :identifier)'; // phpcs:ignore
        $values       = ['identifier' => [$identifier, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'         => $id,
                'identifier' => $identifier,
                'title'      => $title,
                'body'       => $body,
                'layout'     => $layout,
                'layout_id'  => $layoutId,
            ],
        ];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getByIdentifier($identifier);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testGetWithLayoutByIdentifiers()
    {
        $id         = '123';
        $identifier = 'foo';
        $title      = 'bar';
        $body       = 'baz';
        $layout     = 'qux';
        $layoutId   = null;

        $sql          = 'SELECT blocks.id, blocks.identifier, blocks.title, blocks.body, blocks.layout_id, COALESCE(layouts.body, blocks.layout) AS layout FROM blocks LEFT JOIN block_layouts AS layouts ON layouts.id = blocks.layout_id WHERE (blocks.deleted = 0) AND (layouts.deleted = 0 OR layouts.deleted IS NULL) AND (blocks.identifier IN (?))'; // phpcs:ignore
        $values       = [[$identifier, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'         => $id,
                'identifier' => $identifier,
                'title'      => $title,
                'body'       => $body,
                'layout'     => $layout,
                'layout_id'  => $layoutId,
            ],
        ];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getWithLayoutByIdentifiers([$identifier]);

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testUpdateWithoutLayoutId()
    {
        $id         = 123;
        $identifier = 'foo';
        $title      = 'bar';
        $body       = 'baz';
        $layout     = 'qux';
        $layoutId   = null;

        $sql    = 'UPDATE blocks AS blocks SET identifier = ?, title = ?, body = ?, layout = ?, layout_id = ? WHERE (id = ?) AND (deleted = 0)'; // phpcs:ignore
        $values = [
            [$identifier, \PDO::PARAM_STR],
            [$title, \PDO::PARAM_STR],
            [$body, \PDO::PARAM_STR],
            [$layout, \PDO::PARAM_STR],
            [$layoutId, \PDO::PARAM_NULL],
            [$id, \PDO::PARAM_INT],
        ];

        $this->prepare($sql, $this->createWriteStatement($values));
        $entity = new Block($id, $identifier, $title, $body, $layout, $layoutId);

        $this->sut->update($entity);
    }

    public function testUpdateWithLayoutId()
    {
        $id         = 123;
        $identifier = 'foo';
        $title      = 'bar';
        $body       = 'baz';
        $layout     = '';
        $layoutId   = 66;

        $sql    = 'UPDATE blocks AS blocks SET identifier = ?, title = ?, body = ?, layout = ?, layout_id = ? WHERE (id = ?) AND (deleted = 0)'; // phpcs:ignore
        $values = [
            [$identifier, \PDO::PARAM_STR],
            [$title, \PDO::PARAM_STR],
            [$body, \PDO::PARAM_STR],
            [$layout, \PDO::PARAM_STR],
            [$layoutId, \PDO::PARAM_INT],
            [$id, \PDO::PARAM_INT],
        ];

        $this->prepare($sql, $this->createWriteStatement($values));
        $entity = new Block($id, $identifier, $title, $body, $layout, $layoutId);

        $this->sut->update($entity);
    }

    /**
     * @param array $expectedData
     * @param Block $entity
     */
    protected function assertEntity(array $expectedData, $entity)
    {
        $this->assertInstanceOf(Block::class, $entity);
        $this->assertEquals($expectedData['id'], $entity->getId());
        $this->assertSame($expectedData['identifier'], $entity->getIdentifier());
        $this->assertSame($expectedData['title'], $entity->getTitle());
        $this->assertSame($expectedData['body'], $entity->getBody());
        $this->assertSame($expectedData['layout'], $entity->getLayout());
        $this->assertSame($expectedData['layout_id'], $entity->getLayoutId());
    }
}
