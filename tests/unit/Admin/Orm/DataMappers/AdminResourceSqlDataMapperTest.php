<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Orm\DataMapper;

use AbterPhp\Admin\Domain\Entities\AdminResource;
use AbterPhp\Admin\Orm\DataMappers\AdminResourceSqlDataMapper;
use AbterPhp\Framework\Orm\DataMapper\SqlDataMapperTest;
use Opulence\Databases\Adapters\Pdo\Connection as Connection;

class AdminResourceSqlDataMapperTest extends SqlDataMapperTest
{
    /** @var AdminResourceSqlDataMapper */
    protected $sut;

    public function setUp()
    {
        $this->connection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->setMethods(['prepare', 'read', 'lastInsertId'])
            ->getMock()
        ;

        $this->sut = new AdminResourceSqlDataMapper($this->connection, $this->connection);
    }

    public function testAdd()
    {
        $nextId     = '123';
        $identifier = 'foo';

        $sql    = 'INSERT INTO admin_resources (identifier) VALUES (?)'; // phpcs:ignore
        $values = [[$identifier, \PDO::PARAM_STR]];

        $this->lastInsertId($nextId);
        $this->prepare($sql, $this->createWriteStatement($values));
        $entity = new AdminResource(0, $identifier);

        $this->sut->add($entity);

        $this->assertSame($nextId, $entity->getId());
    }

    public function testDelete()
    {
        $id         = 123;
        $identifier = 'foo';

        $sql    = 'UPDATE admin_resources AS admin_resources SET deleted = ? WHERE (id = ?)'; // phpcs:ignore
        $values = [[1, \PDO::PARAM_INT], [$id, \PDO::PARAM_INT]];

        $this->prepare($sql, $this->createWriteStatement($values));
        $entity = new AdminResource($id, $identifier);

        $this->sut->delete($entity);
    }

    public function testGetAll()
    {
        $id         = '123';
        $identifier = 'foo';

        $sql          = 'SELECT admin_resources.id, admin_resources.identifier FROM admin_resources WHERE (admin_resources.deleted = 0)'; // phpcs:ignore
        $values       = [];
        $expectedData = [['id' => $id, 'identifier' => $identifier]];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getAll();

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetById()
    {
        $id         = '123';
        $identifier = 'foo';

        $sql          = 'SELECT admin_resources.id, admin_resources.identifier FROM admin_resources WHERE (admin_resources.deleted = 0) AND (admin_resources.id = :admin_resource_id)'; // phpcs:ignore
        $values       = ['admin_resource_id' => [$id, \PDO::PARAM_INT]];
        $expectedData = [['id' => $id, 'identifier' => $identifier]];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getById($id);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testGetByIdentifier()
    {
        $id         = '123';
        $identifier = 'foo';

        $sql          = 'SELECT admin_resources.id, admin_resources.identifier FROM admin_resources WHERE (admin_resources.deleted = 0) AND (admin_resources.identifier = :identifier)'; // phpcs:ignore
        $values       = ['identifier' => [$identifier, \PDO::PARAM_STR]];
        $expectedData = [['id' => $id, 'identifier' => $identifier]];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getByIdentifier($identifier);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testUpdate()
    {
        $id         = 123;
        $identifier = 'foo';

        $sql    = 'UPDATE admin_resources AS admin_resources SET identifier = ? WHERE (id = ?) AND (deleted = 0)'; // phpcs:ignore
        $values = [[$identifier, \PDO::PARAM_STR], [$id, \PDO::PARAM_INT]];

        $this->prepare($sql, $this->createWriteStatement($values));
        $entity = new AdminResource($id, $identifier);

        $this->sut->update($entity);
    }

    /**
     * @param array         $expectedData
     * @param AdminResource $entity
     */
    protected function assertEntity(array $expectedData, $entity)
    {
        $this->assertInstanceOf(AdminResource::class, $entity);
        $this->assertEquals($expectedData['id'], $entity->getId());
        $this->assertSame($expectedData['identifier'], $entity->getIdentifier());
    }
}
