<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Tests\Orm;

use AbterPhp\Admin\Domain\Entities\Token as Entity;
use AbterPhp\Admin\Orm\TokenRepo;
use AbterPhp\Admin\Tests\TestCase\Orm\RepoTestCase;
use DateTimeImmutable;
use Opulence\Orm\IEntity;

class TokenRepoTest extends RepoTestCase
{
    /** @var TokenRepo - System Under Test */
    protected TokenRepo $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new TokenRepo($this->writerMock, $this->queryBuilder);
    }

    public function testAddWithRevokedAt()
    {
        $this->markTestIncomplete();
//        $entityStub = new Entity('foo0', 'foo-0', new \DateTimeImmutable(), new \DateTimeImmutable());
//
//        $this->unitOfWorkMock->expects($this->once())->method('scheduleForInsertion')->with($entityStub);
//
//        $this->sut->add($entityStub);
    }

    public function testGetByClientId()
    {
        $this->markTestIncomplete();
//        $identifier = 'foo-0';
//        $entityStub = new Entity('foo0', $identifier, new \DateTimeImmutable(), null);
//
//        $entityRegistry = $this->createEntityRegistryStub(null);
//
//        $this->dataMapperMock->expects($this->once())->method('getByClientId')->willReturn($entityStub);
//
//        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);
//
//        $actualResult = $this->sut->getByClientId($identifier);
//
//        $this->assertSame($entityStub, $actualResult);
    }

    /**
     * @return array
     */
    protected function getStubRows(): array
    {
        $rows   = [];
        $rows[] = [
            'id'            => 'foo',
            'api_client_id' => 'foo-api_client_id',
            'expires_at'    => '2030-01-01 00:00:00',
            'revoked_at'    => null,
        ];
        $rows[] = [
            'id'            => 'bar',
            'api_client_id' => 'bar-api_client_id',
            'expires_at'    => '2030-01-01 00:00:00',
            'revoked_at'    => '2021-07-06 01:00:00',
        ];

        return $rows;
    }

    /**
     * @param int $i
     *
     * @return Entity
     */
    protected function createEntityStub(int $i = 0): IEntity
    {
        $rows = $this->getStubRows();
        $row  = $rows[$i];

        $expiresAt = new DateTimeImmutable($row['expires_at']);
        $revokedAt = null;
        if (null !== $row['revoked_at']) {
            $revokedAt = new DateTimeImmutable($row['revoked_at']);
        }

        return new Entity(
            $row['id'],
            $row['api_client_id'],
            $expiresAt,
            $revokedAt
        );
    }
}
