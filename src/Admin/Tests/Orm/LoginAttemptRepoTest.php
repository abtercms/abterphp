<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Tests\Orm;

use AbterPhp\Admin\Domain\Entities\LoginAttempt as Entity;
use AbterPhp\Admin\Orm\LoginAttemptRepo;
use AbterPhp\Admin\Tests\TestCase\Orm\RepoTestCase;

class LoginAttemptRepoTest extends RepoTestCase
{
    /** @var LoginAttemptRepo - System Under Test */
    protected LoginAttemptRepo $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new LoginAttemptRepo($this->writerMock, $this->queryBuilder);
    }

    /**
     * @return array
     */
    protected function getStubRows(): array
    {
        $rows   = [];
        $rows[] = [
            'id'         => 'foo',
            'ip_hash'    => 'foo-ip_hash',
            'username'   => 'foo-username',
            'ip_address' => 'foo-ip_address',
        ];
        $rows[] = [
            'id'         => 'bar',
            'ip_hash'    => 'bar-ip_hash',
            'username'   => 'bar-username',
            'ip_address' => null,
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

        return new Entity(
            $row['id'],
            $row['ip_hash'],
            $row['username'],
            $row['ip_address']
        );
    }
}
