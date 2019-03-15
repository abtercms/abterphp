<?php

declare(strict_types=1);

namespace AbterPhp\Files\Orm\DataMapper;

use AbterPhp\Admin\Domain\Entities\User;
use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Admin\Domain\Entities\UserLanguage;
use AbterPhp\Files\Domain\Entities\File;
use AbterPhp\Files\Domain\Entities\FileDownload;
use AbterPhp\Files\Orm\DataMappers\FileDownloadSqlDataMapper;
use AbterPhp\Framework\Orm\DataMapper\SqlDataMapperTest;
use Opulence\Databases\Adapters\Pdo\Connection as Connection;

class FileDownloadSqlDataMapperTest extends SqlDataMapperTest
{
    /** @var FileDownloadSqlDataMapper */
    protected $sut;

    public function setUp()
    {
        $this->connection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->setMethods(['prepare', 'read', 'lastInsertId'])
            ->getMock();

        $this->sut = new FileDownloadSqlDataMapper($this->connection, $this->connection);
    }

    public function testAdd()
    {
        $nextId       = 123;
        $fileId       = 66;
        $userId       = 876;
        $downloadedAt = new \DateTime();

        $sql    = 'INSERT INTO file_downloads (file_id, user_id, downloaded_at) VALUES (?, ?, ?)'; // phpcs:ignore
        $values = [
            [$fileId, \PDO::PARAM_INT],
            [$userId, \PDO::PARAM_INT],
            [$downloadedAt->format(FileDownload::DATE_FORMAT), \PDO::PARAM_STR],
        ];

        $this->lastInsertId($nextId);
        $this->prepare($sql, $this->createWriteStatement($values));
        $entity = $this->createEntity(0, $fileId, $userId, $downloadedAt);

        $this->sut->add($entity);

        $this->assertSame($nextId, $entity->getId());
    }

    public function testDelete()
    {
        $id           = 123;
        $fileId       = 66;
        $userId       = 876;
        $downloadedAt = new \DateTime();

        $sql    = 'UPDATE file_downloads AS file_downloads SET deleted = ? WHERE (id = ?)'; // phpcs:ignore
        $values = [[1, \PDO::PARAM_INT], [$id, \PDO::PARAM_INT]];

        $this->prepare($sql, $this->createWriteStatement($values));
        $entity = $this->createEntity($id, $fileId, $userId, $downloadedAt);

        $this->sut->delete($entity);
    }

    public function testGetAll()
    {
        $id             = 123;
        $fileId         = 66;
        $userId         = 876;
        $downloadedAt   = new \DateTime();
        $filesystemName = 'foo';
        $publicName     = 'bar';
        $userName       = 'baz';

        $sql          = 'SELECT file_downloads.id, file_downloads.file_id, file_downloads.user_id, file_downloads.downloaded_at, files.filesystem_name AS filesystem_name, files.public_name AS public_name, users.username AS username FROM file_downloads INNER JOIN files AS files ON files.id=file_downloads.file_id INNER JOIN users AS users ON users.id=file_downloads.user_id WHERE (file_downloads.deleted = 0)'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'              => $id,
                'file_id'         => $fileId,
                'user_id'         => $userId,
                'downloaded_at'   => $downloadedAt->format(FileDownload::DATE_FORMAT),
                'filesystem_name' => $filesystemName,
                'public_name'     => $publicName,
                'username'        => $userName,
            ],
        ];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getAll();

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetById()
    {
        $id             = 123;
        $fileId         = 66;
        $userId         = 876;
        $downloadedAt   = new \DateTime();
        $filesystemName = 'foo';
        $publicName     = 'bar';
        $userName       = 'baz';

        $sql          = 'SELECT file_downloads.id, file_downloads.file_id, file_downloads.user_id, file_downloads.downloaded_at, files.filesystem_name AS filesystem_name, files.public_name AS public_name, users.username AS username FROM file_downloads INNER JOIN files AS files ON files.id=file_downloads.file_id INNER JOIN users AS users ON users.id=file_downloads.user_id WHERE (file_downloads.deleted = 0) AND (file_downloads.id = :file_download_id)'; // phpcs:ignore
        $values       = ['file_download_id' => [$id, \PDO::PARAM_INT]];
        $expectedData = [
            [
                'id'              => $id,
                'file_id'         => $fileId,
                'user_id'         => $userId,
                'downloaded_at'   => $downloadedAt->format(FileDownload::DATE_FORMAT),
                'filesystem_name' => $filesystemName,
                'public_name'     => $publicName,
                'username'        => $userName,
            ],
        ];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getById($id);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testUpdate()
    {
        $id           = 123;
        $fileId       = 66;
        $userId       = 876;
        $downloadedAt = new \DateTime();

        $sql    = 'UPDATE file_downloads AS file_downloads SET file_id = ?, user_id = ?, downloaded_at = ? WHERE (id = ?)'; // phpcs:ignore
        $values = [
            [$fileId, \PDO::PARAM_INT],
            [$userId, \PDO::PARAM_INT],
            [$downloadedAt->format(FileDownload::DATE_FORMAT), \PDO::PARAM_STR],
            [$id, \PDO::PARAM_INT],
        ];

        $this->prepare($sql, $this->createWriteStatement($values));
        $entity = $this->createEntity($id, $fileId, $userId, $downloadedAt);

        $this->sut->update($entity);
    }

    public function testGetByFileId()
    {
        $id             = 123;
        $fileId         = 66;
        $userId         = 876;
        $downloadedAt   = new \DateTime();
        $filesystemName = 'foo';
        $publicName     = 'bar';
        $userName       = 'baz';

        $sql          = 'SELECT file_downloads.id, file_downloads.file_id, file_downloads.user_id, file_downloads.downloaded_at, files.filesystem_name AS filesystem_name, files.public_name AS public_name, users.username AS username FROM file_downloads INNER JOIN files AS files ON files.id=file_downloads.file_id INNER JOIN users AS users ON users.id=file_downloads.user_id WHERE (file_downloads.deleted = 0) AND (file_id = :file_id)'; // phpcs:ignore
        $values       = ['file_id' => [$fileId, \PDO::PARAM_INT]];
        $expectedData = [
            [
                'id'              => $id,
                'file_id'         => $fileId,
                'user_id'         => $userId,
                'downloaded_at'   => $downloadedAt->format(FileDownload::DATE_FORMAT),
                'filesystem_name' => $filesystemName,
                'public_name'     => $publicName,
                'username'        => $userName,
            ],
        ];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getByFileId($fileId);

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetByUserId()
    {
        $id             = 123;
        $fileId         = 66;
        $userId         = 876;
        $downloadedAt   = new \DateTime();
        $filesystemName = 'foo';
        $publicName     = 'bar';
        $userName       = 'baz';

        $sql          = 'SELECT file_downloads.id, file_downloads.file_id, file_downloads.user_id, file_downloads.downloaded_at, files.filesystem_name AS filesystem_name, files.public_name AS public_name, users.username AS username FROM file_downloads INNER JOIN files AS files ON files.id=file_downloads.file_id INNER JOIN users AS users ON users.id=file_downloads.user_id WHERE (file_downloads.deleted = 0) AND (user_id = :user_id)'; // phpcs:ignore
        $values       = ['user_id' => [$userId, \PDO::PARAM_INT]];
        $expectedData = [
            [
                'id'              => $id,
                'file_id'         => $fileId,
                'user_id'         => $userId,
                'downloaded_at'   => $downloadedAt->format(FileDownload::DATE_FORMAT),
                'filesystem_name' => $filesystemName,
                'public_name'     => $publicName,
                'username'        => $userName,
            ],
        ];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getByUserId($userId);

        $this->assertCollection($expectedData, $actualResult);
    }

    /**
     * @param int       $id
     * @param int       $fileId
     * @param int       $userId
     * @param \DateTime $downloadedAt
     *
     * @return FileDownload
     * @throws \Exception
     */
    protected function createEntity(int $id, int $fileId, int $userId, \DateTime $downloadedAt)
    {
        $file         = new File($fileId, '', '', '');
        $userGroup    = new UserGroup(0, '', '');
        $userLanguage = new UserLanguage(0, '', '');
        $user         = new User($userId, '', '', '', $userGroup, $userLanguage, true, true);

        return new FileDownload($id, $file, $user, $downloadedAt);
    }

    /**
     * @param array        $expectedData
     * @param FileDownload $entity
     */
    protected function assertEntity(array $expectedData, $entity)
    {
        $downloadedAt = $entity->getDownloadedAt()->format(FileDownload::DATE_FORMAT);

        $this->assertInstanceOf(FileDownload::class, $entity);
        $this->assertEquals($expectedData['id'], $entity->getId());
        $this->assertSame($expectedData['file_id'], $entity->getFile()->getId());
        $this->assertSame($expectedData['user_id'], $entity->getUser()->getId());
        $this->assertSame($expectedData['downloaded_at'], $downloadedAt);
        $this->assertSame($expectedData['filesystem_name'], $entity->getFile()->getFilesystemName());
        $this->assertSame($expectedData['public_name'], $entity->getFile()->getPublicName());
        $this->assertSame($expectedData['username'], $entity->getUser()->getUsername());
    }
}
