<?php

declare(strict_types=1);

namespace AbterPhp\Files\Tests\Service\RepoGrid;

use AbterPhp\Files\Service\RepoGrid\File;
use AbterPhp\Framework\Grid\IGrid;
use AbterPhp\Files\Grid\Factory\File as GridFactory;
use AbterPhp\Files\Orm\FileRepo as Repo;
use Casbin\Enforcer;
use Opulence\Http\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    /** @var File - System Under Test */
    protected File $sut;

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

        $this->sut = new File(
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
