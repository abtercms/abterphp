<?php

declare(strict_types=1);

namespace AbterPhp\Files\Tests\Orm;

use AbterPhp\Admin\Domain\Entities\User;
use AbterPhp\Admin\Domain\Entities\UserLanguage;
use AbterPhp\Admin\Tests\TestCase\Orm\GridRepoTestCase;
use AbterPhp\Files\Domain\Entities\File as Entity;
use AbterPhp\Files\Orm\FileRepo;
use PDO;
use QB\MySQL\Statement\Select;

class FileRepoTest extends GridRepoTestCase
{
    /** @var FileRepo - System Under Test */
    protected FileRepo $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new FileRepo($this->writerMock, $this->queryBuilder);
    }

    /**
     * @return array<int,array<string,string>>
     */
    protected function getStubRows(): array
    {
        $rows   = [];
        $rows[] = [
            'id'                       => 'foo',
            'file_category_id'         => 'foo-category-id',
            'file_category_identifier' => 'foo-category-identifier',
            'file_category_name'       => 'FooCat',
            'filesystem_name'          => 'fn1',
            'public_name'              => 'pn1',
            'mime'                     => 'text/foo',
            'description'              => 'Fooish',
            'uploaded_at'              => '2021-02-03 04:05:06',
        ];
        $rows[] = [
            'id'                       => 'bar',
            'file_category_id'         => 'bar-category-id',
            'file_category_identifier' => 'bar-category-identifier',
            'file_category_name'       => 'BarCat',
            'filesystem_name'          => 'fn2',
            'public_name'              => 'pn2',
            'mime'                     => 'text/bar',
            'description'              => 'Barish',
            'uploaded_at'              => '2021-03-04 05:06:07',
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

        return new Entity($row['id'], $row['filesystem_name'], $row['public_name'], $row['mime'], $row['description']);
    }

    public function testGetByUser()
    {
        $userId   = 'baz';
        $language = new UserLanguage('', '', '');
        $user     = new User($userId, '', '', '', false, false, $language);

        $rows = $this->getStubRows();

        $this->writerMock
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturnCallback(function (Select $a) use ($rows, $userId) {
                $this->assertStringContainsString('SELECT', (string)$a);
                $this->assertContains([$userId, PDO::PARAM_STR], $a->getParams());
                return $rows;
            });

        $actualResult = $this->sut->getByUser($user);

        $this->assertCount(2, $actualResult);
        $this->assertSame('foo', $actualResult[0]->getId());
        $this->assertSame('bar', $actualResult[1]->getId());
    }

    public function testGetByFilesystemName()
    {
        $filesystemName = 'baz';

        $rows = $this->getStubRows();
        $row  = $rows[0];

        $this->writerMock
            ->expects($this->once())
            ->method('fetch')
            ->willReturnCallback(function (Select $a) use ($row, $filesystemName) {
                $this->assertStringContainsString('SELECT', (string)$a);
                $this->assertContains([$filesystemName, PDO::PARAM_STR], $a->getParams());
                return $row;
            });

        $actualResult = $this->sut->getByFilesystemName($filesystemName);

        $this->assertSame('foo', $actualResult->getId());
    }

    public function testGetPublicByFilesystemName()
    {
        $fsName = 'fs-1';

        $rows = $this->getStubRows();
        $row  = $rows[0];

        $this->writerMock
            ->expects($this->once())
            ->method('fetch')
            ->willReturnCallback(function (Select $a) use ($row, $fsName) {
                $this->assertStringContainsString('SELECT', (string)$a);
                $this->assertContains([$fsName, PDO::PARAM_STR], $a->getParams());
                return $row;
            });

        $actualResult = $this->sut->getPublicByFilesystemName($fsName);

        $this->assertSame('foo', $actualResult->getId());
    }

    public function testGetPublicByCategoryIdentifiers()
    {
        $categoryIdentifiers = ['fc-1', 'fc-2'];

        $rows = $this->getStubRows();

        $this->writerMock
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturnCallback(function (Select $a) use ($rows, $categoryIdentifiers) {
                $this->assertStringContainsString('SELECT', (string)$a);
                $this->assertContains([$categoryIdentifiers[0], PDO::PARAM_STR], $a->getParams());
                $this->assertContains([$categoryIdentifiers[1], PDO::PARAM_STR], $a->getParams());
                return $rows;
            });

        $actualResult = $this->sut->getPublicByCategoryIdentifiers($categoryIdentifiers);

        $this->assertCount(2, $actualResult);
        $this->assertSame('foo', $actualResult[0]->getId());
        $this->assertSame('bar', $actualResult[1]->getId());
    }
}
