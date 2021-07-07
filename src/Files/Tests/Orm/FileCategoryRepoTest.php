<?php

declare(strict_types=1);

namespace AbterPhp\Files\Tests\Orm;

use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Admin\Tests\TestCase\Orm\GridRepoTestCase;
use AbterPhp\Files\Domain\Entities\FileCategory as Entity;
use AbterPhp\Files\Orm\FileCategoryRepo;
use PDO;
use QB\MySQL\Statement\Select;

class FileCategoryRepoTest extends GridRepoTestCase
{
    /** @var FileCategoryRepo - System Under Test */
    protected FileCategoryRepo $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new FileCategoryRepo($this->writerMock, $this->queryBuilder);
    }

    /**
     * @return array<int,array<string,string>>
     */
    protected function getStubRows(): array
    {
        $rows   = [];
        $rows[] = [
            'id'             => 'foo',
            'identifier'     => 'foo-identifier',
            'name'           => 'foo-name',
            'is_public'      => 'false',
            'user_group_ids' => 'ug1,ug2',
        ];
        $rows[] = [
            'id'             => 'bar',
            'identifier'     => 'bar-identifier',
            'name'           => 'bar-name',
            'is_public'      => 'true',
            'user_group_ids' => 'ug1,ug3',
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

        $userGroups = [];
        foreach (explode(',', $row['user_group_ids']) as $ugId) {
            $userGroups[] = new UserGroup($ugId, "$ugId-identifier", "$ugId-name");
        }

        return new Entity($row['id'], $row['identifier'], $row['name'], (bool)$row['is_public'], $userGroups);
    }

    public function testGetByUserGroup()
    {
        $rows       = $this->getStubRows();
        $userGroups = $this->createEntityStub()->getUserGroups();

        $this->writerMock
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturnCallback(function (Select $a) use ($rows, $userGroups) {
                $this->assertStringContainsString('SELECT', (string)$a);
                $this->assertContains([$userGroups[0]->getId(), PDO::PARAM_STR], $a->getParams());
                return $rows;
            });

        $actualResult = $this->sut->getByUserGroup($userGroups[0]);

        $this->assertCount(2, $actualResult);
        $this->assertSame($rows[0]['id'], $actualResult[0]->getId());
        $this->assertSame($rows[1]['id'], $actualResult[1]->getId());
    }
}
