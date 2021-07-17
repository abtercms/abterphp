<?php

declare(strict_types=1);

namespace AbterPhp\Website\Tests\Orm;

use AbterPhp\Admin\Tests\TestCase\Orm\GridRepoTestCase;
use AbterPhp\Website\Domain\Entities\PageLayout as Entity;
use AbterPhp\Website\Orm\PageLayoutRepo;

class PageLayoutRepoTest extends GridRepoTestCase
{
    /** @var PageLayoutRepo - System Under Test */
    protected PageLayoutRepo $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new PageLayoutRepo($this->writerMock, $this->queryBuilder);
    }

    /**
     * @return array<int,array<string,string>>
     */
    protected function getStubRows(): array
    {
        $rows   = [];
        $rows[] = [
            'id'         => 'foo',
            'name'       => 'foo-name',
            'identifier' => 'foo-identifier',
            'classes'    => 'foo-classes',
            'body'       => 'foo-body',
            'header'     => 'foo-header',
            'footer'     => 'foo-footer',
            'css_files'  => 'foo-css_files',
            'js_files'   => 'foo-js_files',
        ];
        $rows[] = [
            'id'         => 'bar',
            'name'       => 'bar-name',
            'identifier' => 'bar-identifier',
            'classes'    => 'bar-classes',
            'body'       => 'bar-body',
            'header'     => 'bar-header',
            'footer'     => 'bar-footer',
            'css_files'  => 'bar-css_files',
            'js_files'   => 'bar-js_files',
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

        $assets = new Entity\Assets(
            $row['identifier'],
            $row['header'],
            $row['footer'],
            explode("\r\n", $row['css_files']),
            explode("\r\n", $row['js_files'])
        );

        return new Entity($row['id'], $row['name'], $row['identifier'], $row['classes'], $row['body'], $assets);
    }

    public function testGetByIdentifier()
    {
        $this->markTestIncomplete();
//        $identifier = 'foo-0';
//
//        $entityStub0 = new Entity('foo0', 'Foo 0', 'foo-0', '', '', null);
//
//        $entityRegistry = $this->createEntityRegistryStub(null);
//
//        $this->dataMapperMock->expects($this->once())->method('getByIdentifier')->willReturn($entityStub0);
//
//        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);
//
//        $actualResult = $this->sut->getByIdentifier($identifier);
//
//        $this->assertSame($entityStub0, $actualResult);
    }
}
