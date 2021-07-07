<?php

declare(strict_types=1);

namespace AbterPhp\Files\Tests\Orm;

use AbterPhp\Admin\Domain\Entities\User;
use AbterPhp\Admin\Domain\Entities\UserLanguage;
use AbterPhp\Admin\Tests\TestCase\Orm\GridRepoTestCase;
use AbterPhp\Files\Domain\Entities\File;
use AbterPhp\Files\Domain\Entities\FileDownload as Entity;
use AbterPhp\Files\Orm\FileDownloadRepo;
use DateTime;
use PDO;
use QB\MySQL\Statement\Select;

class FileDownloadRepoTest extends GridRepoTestCase
{
    /** @var FileDownloadRepo - System Under Test */
    protected FileDownloadRepo $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new FileDownloadRepo($this->writerMock, $this->queryBuilder);
    }

    /**
     * @return array<int,array<string,string>>
     */
    protected function getStubRows(): array
    {
        $rows   = [];
        $rows[] = [
            'id'              => 'foo',
            'file_id'         => 'file-foo',
            'user_id'         => 'user-foo',
            'downloaded_at'   => '2021-02-03 04:05:06',
            'filesystem_name' => 'fn1',
            'public_name'     => 'pn1',
            'mime'            => 'text/foo',
            'username'        => 'Foo',
        ];
        $rows[] = [
            'id'              => 'bar',
            'file_id'         => 'file-bar',
            'user_id'         => 'user-bar',
            'downloaded_at'   => '2021-03-04 05:06:07',
            'filesystem_name' => 'fn2',
            'public_name'     => 'pn2',
            'mime'            => 'text/bar',
            'username'        => 'Bar',
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

        $file         = new File($row['file_id'], $row['filesystem_name'], $row['public_name'], $row['mime'], '');
        $language     = new UserLanguage('', '', '');
        $user         = new User($row['user_id'], $row['username'], '', '', false, false, $language);
        $downloadedAt = DateTime::createFromFormat('Y-m-d H:i:s', $row['downloaded_at']);

        return new Entity($row['id'], $file, $user, $downloadedAt);
    }

    public function testGetByFile()
    {
        $rows = $this->getStubRows();
        $file = $this->createEntityStub()->getFile();

        $this->writerMock
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturnCallback(function (Select $a) use ($rows, $file) {
                $this->assertStringContainsString('SELECT', (string)$a);
                $this->assertContains([$file->getId(), PDO::PARAM_STR], $a->getParams());
                return $rows;
            });

        $actualResult = $this->sut->getByFile($file);

        $this->assertCount(2, $actualResult);
        $this->assertSame('foo', $actualResult[0]->getId());
        $this->assertSame('bar', $actualResult[1]->getId());
    }

    public function testGetByUser()
    {
        $rows = $this->getStubRows();
        $user = $this->createEntityStub()->getUser();

        $this->writerMock
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturnCallback(function (Select $a) use ($rows, $user) {
                $this->assertStringContainsString('SELECT', (string)$a);
                $this->assertContains([$user->getId(), PDO::PARAM_STR], $a->getParams());
                return $rows;
            });

        $actualResult = $this->sut->getByUser($user);

        $this->assertCount(2, $actualResult);
        $this->assertSame('foo', $actualResult[0]->getId());
        $this->assertSame('bar', $actualResult[1]->getId());
    }
}
