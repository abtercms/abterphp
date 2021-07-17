<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Tests\Orm;

use AbterPhp\Admin\Domain\Entities\ApiClient as Entity;
use AbterPhp\Admin\Orm\ApiClientRepo;
use AbterPhp\Admin\Tests\TestCase\Orm\GridRepoTestCase;

class ApiClientRepoTest extends GridRepoTestCase
{
    /** @var ApiClientRepo - System Under Test */
    protected ApiClientRepo $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new ApiClientRepo($this->writerMock, $this->queryBuilder);

        $this->markTestIncomplete();
    }

    /**
     * @return array
     */
    protected function getStubRows(): array
    {
        $rows   = [];
        $rows[] = [
            'id'          => 'foo',
            'user_id'     => 'foo-user_id',
            'description' => 'foo-description',
            'secret'      => 'foo-secret',
        ];
        $rows[] = [
            'id'          => 'bar',
            'user_id'     => 'bar-user_id',
            'description' => 'bar-description',
            'secret'      => 'bar-secret',
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
            $row['user_id'],
            $row['description'],
            $row['secret']
        );
    }
}
