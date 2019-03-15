<?php

declare(strict_types=1);

namespace AbterPhp\Files\Orm\DataMapper;

use AbterPhp\Files\Domain\Entities\File;
use AbterPhp\Files\Domain\Entities\FileCategory;
use AbterPhp\Files\Orm\DataMappers\FileSqlDataMapper;
use AbterPhp\Framework\Orm\DataMapper\SqlDataMapperTest;
use Opulence\Databases\Adapters\Pdo\Connection as Connection;

class FileSqlDataMapperTest extends SqlDataMapperTest
{
    /** @var FileSqlDataMapper */
    protected $sut;

    public function setUp()
    {
        $this->connection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->setMethods(['prepare', 'read', 'lastInsertId'])
            ->getMock();

        $this->sut = new FileSqlDataMapper($this->connection, $this->connection);
    }

    public function testDelete()
    {
        $id                 = 123;
        $filesystemName     = 'foo';
        $publicName         = 'bar';
        $description        = 'baz';
        $categoryId         = 66;
        $categoryName       = 'qux';
        $categoryIdentifier = 'quuux';
        $categoryIsPublic   = false;
        $uploadedAt         = new \DateTime();

        $sql    = 'UPDATE files AS files SET deleted = ? WHERE (id = ?)'; // phpcs:ignore
        $values = [[1, \PDO::PARAM_INT], [$id, \PDO::PARAM_INT]];

        $this->prepare($sql, $this->createWriteStatement($values));
        $category = new FileCategory($categoryId, $categoryIdentifier, $categoryName, $categoryIsPublic);
        $entity   = new File($id, $filesystemName, $publicName, $description, $category, $uploadedAt);

        $this->sut->delete($entity);
    }

    public function testGetAll()
    {
        $id                 = 123;
        $filesystemName     = 'foo';
        $publicName         = 'bar';
        $description        = 'baz';
        $categoryId         = 66;
        $categoryName       = 'qux';
        $categoryIdentifier = 'quuux';
        $uploadedAt         = new \DateTime();

        $sql          = 'SELECT files.id, files.filesystem_name, files.public_name, files.file_category_id, files.description, files.uploaded_at, file_categories.name AS file_category_name, file_categories.identifier AS file_category_identifier FROM files INNER JOIN file_categories AS file_categories ON file_categories.id = files.file_category_id AND file_categories.deleted =0 WHERE (files.deleted = 0) GROUP BY files.id'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'                       => $id,
                'filesystem_name'          => $filesystemName,
                'public_name'              => $publicName,
                'file_category_id'         => $categoryId,
                'description'              => $description,
                'uploaded_at'              => $uploadedAt->format(File::DATE_FORMAT),
                'file_category_name'       => $categoryName,
                'file_category_identifier' => $categoryIdentifier,
            ],
        ];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getAll();

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetById()
    {
        $id                 = 123;
        $filesystemName     = 'foo';
        $publicName         = 'bar';
        $description        = 'baz';
        $categoryId         = 66;
        $categoryName       = 'qux';
        $categoryIdentifier = 'quuux';
        $uploadedAt         = new \DateTime();

        $sql          = 'SELECT files.id, files.filesystem_name, files.public_name, files.file_category_id, files.description, files.uploaded_at, file_categories.name AS file_category_name, file_categories.identifier AS file_category_identifier FROM files INNER JOIN file_categories AS file_categories ON file_categories.id = files.file_category_id AND file_categories.deleted =0 WHERE (files.deleted = 0) AND (files.id = :file_id) GROUP BY files.id'; // phpcs:ignore
        $values       = ['file_id' => [$id, \PDO::PARAM_INT]];
        $expectedData = [
            [
                'id'                       => $id,
                'filesystem_name'          => $filesystemName,
                'public_name'              => $publicName,
                'file_category_id'         => $categoryId,
                'description'              => $description,
                'uploaded_at'              => $uploadedAt->format(File::DATE_FORMAT),
                'file_category_name'       => $categoryName,
                'file_category_identifier' => $categoryIdentifier,
            ],
        ];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getById($id);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testGetByUserId()
    {
        $userId = 93;

        $id                 = 123;
        $filesystemName     = 'foo';
        $publicName         = 'bar';
        $description        = 'baz';
        $categoryId         = 66;
        $categoryName       = 'qux';
        $categoryIdentifier = 'quuux';
        $uploadedAt         = new \DateTime();

        $sql          = 'SELECT files.id, files.filesystem_name, files.public_name, files.file_category_id, files.description, files.uploaded_at, file_categories.name AS file_category_name, file_categories.identifier AS file_category_identifier FROM files INNER JOIN file_categories AS file_categories ON file_categories.id = files.file_category_id AND file_categories.deleted =0 INNER JOIN user_groups_file_categories AS ugfc ON file_categories.id = ugfc.file_category_id AND file_categories.deleted = 0 INNER JOIN user_groups AS user_groups ON user_groups.id = ugfc.user_group_id AND user_groups.deleted = 0 WHERE (files.deleted = 0) AND (user_groups.user_id = :user_id) GROUP BY files.id'; // phpcs:ignore
        $values       = ['user_id' => [$userId, \PDO::PARAM_INT]];
        $expectedData = [
            [
                'id'                       => $id,
                'filesystem_name'          => $filesystemName,
                'public_name'              => $publicName,
                'file_category_id'         => $categoryId,
                'description'              => $description,
                'uploaded_at'              => $uploadedAt->format(File::DATE_FORMAT),
                'file_category_name'       => $categoryName,
                'file_category_identifier' => $categoryIdentifier,
            ],
        ];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getByUserId($userId);

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetAllByUsername()
    {
        $username = 'johndoe';

        $id                 = 123;
        $filesystemName     = 'foo';
        $publicName         = 'bar';
        $description        = 'baz';
        $categoryId         = 66;
        $categoryName       = 'qux';
        $categoryIdentifier = 'quuux';
        $uploadedAt         = new \DateTime();
        $id2                = 124;

        $sql          = 'SELECT files.id, files.filesystem_name, files.public_name, files.file_category_id, files.description, files.uploaded_at, file_categories.name AS file_category_name, file_categories.identifier AS file_category_identifier FROM files INNER JOIN file_categories AS file_categories ON file_categories.id = files.file_category_id AND file_categories.deleted =0 INNER JOIN user_groups_file_categories AS ugfc ON file_categories.id = ugfc.file_category_id AND file_categories.deleted = 0 INNER JOIN user_groups AS user_groups ON user_groups.id = ugfc.user_group_id AND user_groups.deleted = 0 INNER JOIN users AS users ON users.user_group_id = user_groups.id AND users.deleted = 0 WHERE (files.deleted = 0) AND (users.username = :username) GROUP BY files.id'; // phpcs:ignore
        $values       = ['username' => [$username, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'                       => $id,
                'filesystem_name'          => $filesystemName,
                'public_name'              => $publicName,
                'file_category_id'         => $categoryId,
                'description'              => $description,
                'uploaded_at'              => $uploadedAt->format(File::DATE_FORMAT),
                'file_category_name'       => $categoryName,
                'file_category_identifier' => $categoryIdentifier,
            ],
            [
                'id'                       => $id2,
                'filesystem_name'          => $filesystemName,
                'public_name'              => $publicName,
                'file_category_id'         => $categoryId,
                'description'              => $description,
                'uploaded_at'              => $uploadedAt->format(File::DATE_FORMAT),
                'file_category_name'       => $categoryName,
                'file_category_identifier' => $categoryIdentifier,
            ],
        ];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getAllByUsername($username);

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testUpdate()
    {
        $id                 = 123;
        $filesystemName     = 'foo';
        $publicName         = 'bar';
        $description        = 'baz';
        $categoryId         = 66;
        $categoryIdentifier = 'quux';
        $categoryName       = 'qux';
        $categoryIsPublic   = false;
        $uploadedAt         = new \DateTime();

        $sql    = 'UPDATE files AS files SET filesystem_name = ?, public_name = ?, description = ?, uploaded_at = ?, file_category_id = ? WHERE (id = ?) AND (deleted = 0)'; // phpcs:ignore
        $values = [
            [$filesystemName, \PDO::PARAM_STR],
            [$publicName, \PDO::PARAM_STR],
            [$description, \PDO::PARAM_STR],
            [$uploadedAt->format(File::DATE_FORMAT), \PDO::PARAM_STR],
            [$categoryId, \PDO::PARAM_INT],
            [$id, \PDO::PARAM_INT],
        ];

        $this->prepare($sql, $this->createWriteStatement($values));
        $category = new FileCategory($categoryId, $categoryIdentifier, $categoryName, $categoryIsPublic);
        $entity   = new File($id, $filesystemName, $publicName, $description, $category, $uploadedAt);

        $this->sut->update($entity);
    }

    /**
     * @param array $expectedData
     * @param File  $entity
     */
    protected function assertEntity(array $expectedData, $entity)
    {
        $uploadedAt = $entity->getUploadedAt()->format(File::DATE_FORMAT);

        $this->assertInstanceOf(File::class, $entity);
        $this->assertEquals($expectedData['id'], $entity->getId());
        $this->assertSame($expectedData['filesystem_name'], $entity->getFilesystemName());
        $this->assertSame($expectedData['public_name'], $entity->getPublicName());
        $this->assertSame($expectedData['file_category_id'], $entity->getCategory()->getId());
        $this->assertSame($expectedData['description'], $entity->getDescription());
        $this->assertSame($expectedData['uploaded_at'], $uploadedAt);
        $this->assertSame($expectedData['file_category_name'], $entity->getCategory()->getName());
        $this->assertSame($expectedData['file_category_identifier'], $entity->getCategory()->getIdentifier());
    }
}
