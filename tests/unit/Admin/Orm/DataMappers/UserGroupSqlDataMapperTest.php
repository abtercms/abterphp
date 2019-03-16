<?php

declare(strict_types=1);

namespace Infrastructure\Orm\DataMapper;

use AbterPhp\Admin\Domain\Entities\AdminResource;
use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Admin\Orm\DataMappers\UserGroupSqlDataMapper;
use AbterPhp\Files\Domain\Entities\FileCategory;
use AbterPhp\Framework\Orm\DataMapper\SqlDataMapperTest;
use Opulence\Databases\Adapters\Pdo\Connection as Connection;

class UserGroupSqlDataMapperTest extends SqlDataMapperTest
{
    /** @var UserGroupSqlDataMapper */
    protected $sut;

    public function setUp()
    {
        $this->connection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->setMethods(['prepare', 'read', 'lastInsertId'])
            ->getMock();

        $this->sut = new UserGroupSqlDataMapper($this->connection, $this->connection);
    }

    public function testAddWithoutRelated()
    {
        $nextId     = '123';
        $identifier = 'foo';
        $name       = 'bar';

        $sql    = 'INSERT INTO user_groups (identifier, name) VALUES (?, ?)'; // phpcs:ignore
        $values = [[$identifier, \PDO::PARAM_STR], [$name, \PDO::PARAM_STR]];
        $this->prepare($sql, $this->createWriteStatement($values));

        $this->lastInsertId($nextId);

        $entity = new UserGroup(0, $identifier, $name);
        $this->sut->add($entity);

        $this->assertSame($nextId, $entity->getId());
    }

    public function testAddWithRelated()
    {
        $nextId         = '123';
        $identifier     = 'foo';
        $name           = 'bar';
        $adminResources = [new AdminResource(38, ''), new AdminResource(84, '')];

        $sql0    = 'INSERT INTO user_groups (identifier, name) VALUES (?, ?)'; // phpcs:ignore
        $values0 = [[$identifier, \PDO::PARAM_STR], [$name, \PDO::PARAM_STR]];
        $this->prepare($sql0, $this->createWriteStatement($values0), 0);

        $this->lastInsertId($nextId, 1);

        $sql1    = 'INSERT INTO user_groups_admin_resources (user_group_id, admin_resource_id) VALUES (?, ?)'; // phpcs:ignore
        $values1 = [[$nextId, \PDO::PARAM_INT], [$adminResources[0]->getId(), \PDO::PARAM_INT]];
        $this->prepare($sql1, $this->createWriteStatement($values1), 2);

        $sql2    = 'INSERT INTO user_groups_admin_resources (user_group_id, admin_resource_id) VALUES (?, ?)'; // phpcs:ignore
        $values2 = [[$nextId, \PDO::PARAM_INT], [$adminResources[1]->getId(), \PDO::PARAM_INT]];
        $this->prepare($sql2, $this->createWriteStatement($values2), 3);

        $entity = new UserGroup(0, $identifier, $name, $adminResources);
        $this->sut->add($entity);

        $this->assertSame($nextId, $entity->getId());
    }

    public function testDelete()
    {
        $id         = 123;
        $identifier = 'foo';
        $name       = 'bar';

        $sql0    = 'DELETE FROM user_groups_admin_resources WHERE (user_group_id = ?)'; // phpcs:ignore
        $values0 = [[$id, \PDO::PARAM_INT]];
        $this->prepare($sql0, $this->createWriteStatement($values0), 0);

        $sql1    = 'UPDATE user_groups AS user_groups SET deleted = ? WHERE (id = ?)'; // phpcs:ignore
        $values1 = [[1, \PDO::PARAM_INT], [$id, \PDO::PARAM_INT]];
        $this->prepare($sql1, $this->createWriteStatement($values1), 1);

        $entity = new UserGroup($id, $identifier, $name);
        $this->sut->delete($entity);
    }

    public function testGetAll()
    {
        $id         = '123';
        $identifier = 'foo';
        $name       = 'bar';

        $sql          = 'SELECT ug.id, ug.identifier, ug.name, GROUP_CONCAT(ugar.admin_resource_id) AS admin_resource_ids FROM user_groups AS ug LEFT JOIN user_groups_admin_resources AS ugar ON ugar.user_group_id = ug.id WHERE (ug.deleted = 0) GROUP BY ug.id'; // phpcs:ignore
        $values       = [];
        $expectedData = [['id' => $id, 'identifier' => $identifier, 'name' => $name]];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getAll();

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetById()
    {
        $id         = '123';
        $identifier = 'foo';
        $name       = 'bar';

        $sql          = 'SELECT ug.id, ug.identifier, ug.name, GROUP_CONCAT(ugar.admin_resource_id) AS admin_resource_ids FROM user_groups AS ug LEFT JOIN user_groups_admin_resources AS ugar ON ugar.user_group_id = ug.id WHERE (ug.deleted = 0) AND (ug.id = :user_group_id) GROUP BY ug.id'; // phpcs:ignore
        $values       = ['user_group_id' => [$id, \PDO::PARAM_INT]];
        $expectedData = [['id' => $id, 'identifier' => $identifier, 'name' => $name]];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getById($id);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testGetByIdentifier()
    {
        $id         = '123';
        $identifier = 'foo';
        $name       = 'bar';

        $sql          = 'SELECT ug.id, ug.identifier, ug.name, GROUP_CONCAT(ugar.admin_resource_id) AS admin_resource_ids FROM user_groups AS ug LEFT JOIN user_groups_admin_resources AS ugar ON ugar.user_group_id = ug.id WHERE (ug.deleted = 0) AND (ug.identifier = :identifier) GROUP BY ug.id'; // phpcs:ignore
        $values       = ['identifier' => [$identifier, \PDO::PARAM_STR]];
        $expectedData = [['id' => $id, 'identifier' => $identifier, 'name' => $name]];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getByIdentifier($identifier);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testUpdateWithoutRelated()
    {
        $id         = 123;
        $identifier = 'foo';
        $name       = 'bar';

        $sql0    = 'UPDATE user_groups AS user_groups SET identifier = ?, name = ? WHERE (id = ?) AND (deleted = 0)'; // phpcs:ignore
        $values0 = [[$identifier, \PDO::PARAM_STR], [$name, \PDO::PARAM_STR], [$id, \PDO::PARAM_INT]];
        $this->prepare($sql0, $this->createWriteStatement($values0), 0);

        $sql1    = 'DELETE FROM user_groups_admin_resources WHERE (user_group_id = ?)'; // phpcs:ignore
        $values1 = [[$id, \PDO::PARAM_INT]];
        $this->prepare($sql1, $this->createWriteStatement($values1), 1);

        $entity = new UserGroup($id, $identifier, $name);
        $this->sut->update($entity);
    }

    public function testUpdateWithRelated()
    {
        $id             = 123;
        $identifier     = 'foo';
        $name           = 'bar';
        $adminResources = [new AdminResource(38, ''), new AdminResource(84, '')];

        $sql0    = 'UPDATE user_groups AS user_groups SET identifier = ?, name = ? WHERE (id = ?) AND (deleted = 0)'; // phpcs:ignore
        $values0 = [[$identifier, \PDO::PARAM_STR], [$name, \PDO::PARAM_STR], [$id, \PDO::PARAM_INT]];
        $this->prepare($sql0, $this->createWriteStatement($values0), 0);

        $sql1    = 'DELETE FROM user_groups_admin_resources WHERE (user_group_id = ?)'; // phpcs:ignore
        $values1 = [[$id, \PDO::PARAM_INT]];
        $this->prepare($sql1, $this->createWriteStatement($values1), 1);

        $sql2    = 'INSERT INTO user_groups_admin_resources (user_group_id, admin_resource_id) VALUES (?, ?)'; // phpcs:ignore
        $values2 = [[$id, \PDO::PARAM_INT], [$adminResources[0]->getId(), \PDO::PARAM_INT]];
        $this->prepare($sql2, $this->createWriteStatement($values2), 2);

        $sql3    = 'INSERT INTO user_groups_admin_resources (user_group_id, admin_resource_id) VALUES (?, ?)'; // phpcs:ignore
        $values3 = [[$id, \PDO::PARAM_INT], [$adminResources[1]->getId(), \PDO::PARAM_INT]];
        $this->prepare($sql3, $this->createWriteStatement($values3), 3);

        $entity = new UserGroup($id, $identifier, $name, $adminResources);
        $this->sut->update($entity);
    }

    /**
     * @param array     $expectedData
     * @param UserGroup $entity
     */
    protected function assertEntity(array $expectedData, $entity)
    {
        $this->assertInstanceOf(UserGroup::class, $entity);
        $this->assertEquals($expectedData['id'], $entity->getId());
        $this->assertSame($expectedData['identifier'], $entity->getIdentifier());
    }
}
