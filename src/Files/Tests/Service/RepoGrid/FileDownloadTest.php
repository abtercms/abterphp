<?php

declare(strict_types=1);

namespace AbterPhp\Files\Tests\Service\RepoGrid;

use AbterPhp\Files\Service\RepoGrid\FileDownload;
use AbterPhp\Framework\Grid\IGrid;
use AbterPhp\Files\Grid\Factory\FileDownload as GridFactory;
use AbterPhp\Files\Orm\FileDownloadRepo as Repo;
use Casbin\Enforcer;
use Opulence\Http\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FileDownloadTest extends TestCase
{
    /** @var FileDownload - System Under Test */
    protected FileDownload $sut;

    /** @var Enforcer|MockObject */
    protected $enforcerMock;

    /** @var Repo|MockObject */
    protected $repoMock;

    /** @var GridFactory|MockObject */
    protected $gridFactoryMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->enforcerMock    = $this->createMock(Enforcer::class);
        $this->repoMock        = $this->createMock(Repo::class);
        $this->gridFactoryMock = $this->createMock(GridFactory::class);

        $this->sut = new FileDownload(
            $this->enforcerMock,
            $this->repoMock,
            $this->gridFactoryMock
        );
    }

    public function testCreateAndPopulate()
    {
        $baseUrl = '/foo';

        /** @var Collection|MockObject $query */
        $queryStub = $this->createMock(Collection::class);

        /** @var IGrid|MockObject $query */
        $gridStub = $this->createMock(IGrid::class);

        $this->gridFactoryMock
            ->expects($this->any())
            ->method('createGrid')
            ->willReturn($gridStub);

        $actualResult = $this->sut->createAndPopulate($queryStub, $baseUrl);

        $this->assertSame($gridStub, $actualResult);
    }
}
