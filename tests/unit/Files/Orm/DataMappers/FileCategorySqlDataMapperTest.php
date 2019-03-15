<?php

declare(strict_types=1);

namespace AbterPhp\Files\Orm\DataMapper;

use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Files\Domain\Entities\FileCategory;
use AbterPhp\Files\Orm\DataMappers\FileCategorySqlDataMapper;
use AbterPhp\Framework\Orm\DataMapper\SqlDataMapperTest;
use Opulence\Databases\Adapters\Pdo\Connection as Connection;

class FileCategorySqlDataMapperTest extends SqlDataMapperTest
{
    /** @var FileCategorySqlDataMapper */
    protected $sut;

    public function setUp()
    {
        $this->connection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->setMethods(['prepare', 'read', 'lastInsertId'])
            ->getMock();

        $this->sut = new FileCategorySqlDataMapper($this->connection, $this->connection);
    }

    public function testAddWithoutUserGroup()
    {
        $nextId     = '123';
        $identifier = 'bar';
        $name       = 'foo';
        $isPublic   = true;

        $sql    = 'INSERT INTO file_categories (identifier, name, is_public) VALUES (?, ?, ?)'; // phpcs:ignore
        $values = [
            [$identifier, \PDO::PARAM_STR],
            [$name, \PDO::PARAM_STR],
            [$isPublic, \PDO::PARAM_BOOL],
        ];
        $this->prepare($sql, $this->createWriteStatement($values), 0);

        $entity = new FileCategory(0, $identifier, $name, $isPublic);
        $this->lastInsertId($nextId, 1);

        $this->sut->add($entity);

        $this->assertSame($nextId, $entity->getId());
    }

    public function testAddWithUserGroup()
    {
        $nextId     = '123';
        $identifier = 'bar';
        $name       = 'foo';
        $isPublic   = true;
        $userGroups = [new UserGroup(58, '', ''), new UserGroup(83, '', '')];

        $sql0    = 'INSERT INTO file_categories (identifier, name, is_public) VALUES (?, ?, ?)'; // phpcs:ignore
        $values0 = [
            [$identifier, \PDO::PARAM_STR],
            [$name, \PDO::PARAM_STR],
            [$isPublic, \PDO::PARAM_BOOL],
        ];
        $this->prepare($sql0, $this->createWriteStatement($values0), 0);

        $entity = new FileCategory(0, $identifier, $name, $isPublic, $userGroups);
        $this->lastInsertId($nextId, 1);

        $sql1    = 'INSERT INTO user_groups_file_categories (user_group_id, file_category_id) VALUES (?, ?)'; // phpcs:ignore
        $values1 = [
            [$userGroups[0]->getId(), \PDO::PARAM_INT],
            [$nextId, \PDO::PARAM_INT],
        ];
        $this->prepare($sql1, $this->createWriteStatement($values1), 2);

        $sql2    = 'INSERT INTO user_groups_file_categories (user_group_id, file_category_id) VALUES (?, ?)'; // phpcs:ignore
        $values2 = [
            [$userGroups[1]->getId(), \PDO::PARAM_INT],
            [$nextId, \PDO::PARAM_INT],
        ];
        $this->prepare($sql2, $this->createWriteStatement($values2), 3);

        $this->sut->add($entity);

        $this->assertSame($nextId, $entity->getId());
    }

    public function testDelete()
    {
        $id         = 123;
        $identifier = 'bar';
        $name       = 'foo';
        $isPublic   = true;

        $sql0    = 'UPDATE file_categories AS file_categories SET deleted = ? WHERE (id = ?)'; // phpcs:ignore
        $values0 = [[1, \PDO::PARAM_INT], [$id, \PDO::PARAM_INT]];
        $this->prepare($sql0, $this->createWriteStatement($values0), 0);

        $entity = new FileCategory($id, $identifier, $name, $isPublic);

        $sql1    = 'DELETE FROM user_groups_file_categories WHERE (file_category_id = ?)'; // phpcs:ignore
        $values1 = [[$id, \PDO::PARAM_INT]];
        $this->prepare($sql1, $this->createWriteStatement($values1), 1);

        $this->sut->delete($entity);
    }

    public function testGetAll()
    {
        $id         = '123';
        $identifier = 'bar';
        $name       = 'foo';
        $isPublic   = true;

        $sql          = 'SELECT fc.id, fc.identifier, fc.name, fc.is_public, GROUP_CONCAT(ugfc.user_group_id) AS user_group_ids FROM file_categories AS fc LEFT JOIN user_groups_file_categories AS ugfc ON ugfc.file_category_id = fc.id WHERE (fc.deleted = 0) GROUP BY fc.id'; // phpcs:ignore
        $values       = [];
        $expectedData = [['id' => $id, 'identifier' => $identifier, 'name' => $name, 'is_public' => $isPublic]];
        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getAll();

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetById()
    {
        $id         = '123';
        $identifier = 'bar';
        $name       = 'foo';
        $isPublic   = true;

        $sql          = 'SELECT fc.id, fc.identifier, fc.name, fc.is_public, GROUP_CONCAT(ugfc.user_group_id) AS user_group_ids FROM file_categories AS fc LEFT JOIN user_groups_file_categories AS ugfc ON ugfc.file_category_id = fc.id WHERE (fc.deleted = 0) AND (fc.id = :file_category_id) GROUP BY fc.id'; // phpcs:ignore
        $values       = ['file_category_id' => [$id, \PDO::PARAM_INT]];
        $expectedData = [['id' => $id, 'identifier' => $identifier, 'name' => $name, 'is_public' => $isPublic]];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getById($id);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testGetByUserGroupId()
    {
        $userGroupId = 66;

        $id         = '123';
        $identifier = 'bar';
        $name       = 'foo';
        $isPublic   = true;

        $sql          = 'SELECT fc.id, fc.identifier, fc.name, fc.is_public, GROUP_CONCAT(ugfc.user_group_id) AS user_group_ids FROM file_categories AS fc INNER JOIN user_groups_file_categories AS ugfc2 ON fc.id = ugfc2.file_category_id LEFT JOIN user_groups_file_categories AS ugfc ON ugfc.file_category_id = fc.id WHERE (fc.deleted = 0) AND (ugfc2.user_group_id = :user_group_id) GROUP BY fc.id'; // phpcs:ignore
        $values       = ['ugfc2.user_group_id' => [$userGroupId, \PDO::PARAM_INT]];
        $expectedData = [['id' => $id, 'identifier' => $identifier, 'name' => $name, 'is_public' => $isPublic]];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getByUserGroupId($userGroupId);

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testUpdateWithoutUserGroup()
    {
        $id         = 123;
        $identifier = 'bar';
        $name       = 'foo';
        $isPublic   = true;

        $sql0    = 'UPDATE file_categories AS file_categories SET identifier = ?, name = ?, is_public = ? WHERE (id = ?) AND (deleted = 0)'; // phpcs:ignore
        $values0 = [
            [$identifier, \PDO::PARAM_STR],
            [$name, \PDO::PARAM_STR],
            [$isPublic, \PDO::PARAM_BOOL],
            [$id, \PDO::PARAM_INT],
        ];
        $this->prepare($sql0, $this->createWriteStatement($values0), 0);

        $entity = new FileCategory($id, $identifier, $name, $isPublic);

        $sql1    = 'DELETE FROM user_groups_file_categories WHERE (file_category_id = ?)'; // phpcs:ignore
        $values1 = [
            [$id, \PDO::PARAM_INT],
        ];
        $this->prepare($sql1, $this->createWriteStatement($values1), 1);

        $this->sut->update($entity);
    }

    public function testUpdateWithUserGroup()
    {
        $id         = 123;
        $identifier = 'bar';
        $name       = 'foo';
        $isPublic   = true;
        $userGroups = [new UserGroup(12, '', ''), new UserGroup(49, '', '')];

        $sql0    = 'UPDATE file_categories AS file_categories SET identifier = ?, name = ?, is_public = ? WHERE (id = ?) AND (deleted = 0)'; // phpcs:ignore
        $values0 = [
            [$identifier, \PDO::PARAM_STR],
            [$name, \PDO::PARAM_STR],
            [$isPublic, \PDO::PARAM_BOOL],
            [$id, \PDO::PARAM_INT],
        ];
        $this->prepare($sql0, $this->createWriteStatement($values0), 0);

        $entity = new FileCategory($id, $identifier, $name, $isPublic, $userGroups);

        $sql1    = 'DELETE FROM user_groups_file_categories WHERE (file_category_id = ?)'; // phpcs:ignore
        $values1 = [
            [$id, \PDO::PARAM_INT],
        ];
        $this->prepare($sql1, $this->createWriteStatement($values1), 1);

        $sql2    = 'INSERT INTO user_groups_file_categories (user_group_id, file_category_id) VALUES (?, ?)'; // phpcs:ignore
        $values2 = [
            [$userGroups[0]->getId(), \PDO::PARAM_INT],
            [$id, \PDO::PARAM_INT],
        ];
        $this->prepare($sql2, $this->createWriteStatement($values2), 2);

        $sql3    = 'INSERT INTO user_groups_file_categories (user_group_id, file_category_id) VALUES (?, ?)'; // phpcs:ignore
        $values3 = [
            [$userGroups[1]->getId(), \PDO::PARAM_INT],
            [$id, \PDO::PARAM_INT],
        ];
        $this->prepare($sql3, $this->createWriteStatement($values3), 3);

        $this->sut->update($entity);
    }

    /**
     * @param array        $expectedData
     * @param FileCategory $entity
     */
    protected function assertEntity(array $expectedData, $entity)
    {
        $this->assertInstanceOf(FileCategory::class, $entity);
        $this->assertEquals($expectedData['id'], $entity->getId());
        $this->assertSame($expectedData['name'], $entity->getName());
        $this->assertSame($expectedData['is_public'], $entity->isPublic());
    }
}
