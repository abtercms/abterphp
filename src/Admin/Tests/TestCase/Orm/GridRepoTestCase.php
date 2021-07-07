<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Tests\TestCase\Orm;

abstract class GridRepoTestCase extends RepoTestCase
{
    public function testGetPage()
    {
        $rows = $this->getStubRows();

        $this->writerMock
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturnCallback(function ($a) use ($rows) {
                $this->assertStringContainsString('SELECT', (string)$a);
                return $rows;
            });

        $actualResult = $this->sut->getPage(0, 10, [], []);

        $this->assertCount(2, $actualResult);
        $this->assertSame('foo', $actualResult[0]->getId());
        $this->assertSame('bar', $actualResult[1]->getId());
    }
}
