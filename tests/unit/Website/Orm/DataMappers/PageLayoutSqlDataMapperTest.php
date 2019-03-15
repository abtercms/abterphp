<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm\DataMapper;

use AbterPhp\Framework\Orm\DataMapper\SqlDataMapperTest;
use AbterPhp\Website\Domain\Entities\PageLayout;
use AbterPhp\Website\Orm\DataMappers\PageLayoutSqlDataMapper;
use Opulence\Databases\Adapters\Pdo\Connection as Connection;

class PageLayoutSqlDataMapperTest extends SqlDataMapperTest
{
    /** @var PageLayoutSqlDataMapper */
    protected $sut;

    public function setUp()
    {
        $this->connection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->setMethods(['prepare', 'read', 'lastInsertId'])
            ->getMock();

        $this->sut = new PageLayoutSqlDataMapper($this->connection, $this->connection);
    }

    public function testAdd()
    {
        $nextId     = '123';
        $identifier = 'foo';
        $body       = 'bar';

        $sql    = 'INSERT INTO page_layouts (identifier, body) VALUES (?, ?)'; // phpcs:ignore
        $values = [[$identifier, \PDO::PARAM_STR], [$body, \PDO::PARAM_STR]];

        $this->lastInsertId($nextId);
        $this->prepare($sql, $this->createWriteStatement($values));
        $entity = new PageLayout(0, $identifier, $body, null);

        $this->sut->add($entity);

        $this->assertSame($nextId, $entity->getId());
    }

    public function testDelete()
    {
        $id         = 123;
        $identifier = 'foo';
        $body       = 'bar';

        $sql    = 'UPDATE page_layouts AS page_layouts SET deleted = ? WHERE (id = ?)'; // phpcs:ignore
        $values = [[1, \PDO::PARAM_INT], [$id, \PDO::PARAM_INT]];

        $this->prepare($sql, $this->createWriteStatement($values));
        $entity = new PageLayout($id, $identifier, $body, null);

        $this->sut->delete($entity);
    }

    public function testGetAll()
    {
        $id         = '123';
        $identifier = 'foo';
        $body       = 'bar';
        $header     = 'baz';
        $footer     = 'yak';
        $cssFiles   = 'zar';
        $jsFiles    = 'boi';

        $sql          = 'SELECT page_layouts.id, page_layouts.identifier, page_layouts.body, page_layouts.header, page_layouts.footer, page_layouts.css_files, page_layouts.js_files FROM page_layouts WHERE (page_layouts.deleted = 0)'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'         => $id,
                'identifier' => $identifier,
                'body'       => $body,
                'header'     => $header,
                'footer'     => $footer,
                'css_files'  => $cssFiles,
                'js_files'   => $jsFiles,
            ],
        ];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getAll();

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetById()
    {
        $id         = '123';
        $identifier = 'foo';
        $body       = 'bar';
        $header     = 'baz';
        $footer     = 'yak';
        $cssFiles   = 'zar';
        $jsFiles    = 'boi';

        $sql          = 'SELECT page_layouts.id, page_layouts.identifier, page_layouts.body, page_layouts.header, page_layouts.footer, page_layouts.css_files, page_layouts.js_files FROM page_layouts WHERE (page_layouts.deleted = 0) AND (page_layouts.id = :layout_id)'; // phpcs:ignore
        $values       = ['layout_id' => [$id, \PDO::PARAM_INT]];
        $expectedData = [
            [
                'id'         => $id,
                'identifier' => $identifier,
                'body'       => $body,
                'header'     => $header,
                'footer'     => $footer,
                'css_files'  => $cssFiles,
                'js_files'   => $jsFiles,
            ],
        ];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getById($id);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testGetByIdentifier()
    {
        $id         = '123';
        $identifier = 'foo';
        $body       = 'bar';
        $header     = 'baz';
        $footer     = 'yak';
        $cssFiles   = 'zar';
        $jsFiles    = 'boi';

        $sql          = 'SELECT page_layouts.id, page_layouts.identifier, page_layouts.body, page_layouts.header, page_layouts.footer, page_layouts.css_files, page_layouts.js_files FROM page_layouts WHERE (page_layouts.deleted = 0) AND (identifier = :identifier)'; // phpcs:ignore
        $values       = ['identifier' => $identifier];
        $expectedData = [
            [
                'id'         => $id,
                'identifier' => $identifier,
                'body'       => $body,
                'header'     => $header,
                'footer'     => $footer,
                'css_files'  => $cssFiles,
                'js_files'   => $jsFiles,
            ],
        ];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getByIdentifier($identifier);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testUpdate()
    {
        $id         = 123;
        $identifier = 'foo';
        $body       = 'bar';
        $header     = 'baz';
        $footer     = 'yak';
        $cssFiles   = 'zar';
        $jsFiles    = 'boi';

        $sql    = 'UPDATE page_layouts AS page_layouts SET identifier = ?, body = ?, header = ?, footer = ?, css_files = ?, js_files = ? WHERE (id = ?) AND (deleted = 0)'; // phpcs:ignore
        $values = [
            [$identifier, \PDO::PARAM_STR],
            [$body, \PDO::PARAM_STR],
            [$header, \PDO::PARAM_STR],
            [$footer, \PDO::PARAM_STR],
            [$cssFiles, \PDO::PARAM_STR],
            [$jsFiles, \PDO::PARAM_STR],
            [$id, \PDO::PARAM_INT],
        ];

        $this->prepare($sql, $this->createWriteStatement($values));
        $entity = new PageLayout(
            $id,
            $identifier,
            $body,
            new PageLayout\Assets($identifier, $header, $footer, (array)$cssFiles, (array)$jsFiles)
        );

        $this->sut->update($entity);
    }

    /**
     * @param array      $expectedData
     * @param PageLayout $entity
     */
    protected function assertEntity(array $expectedData, $entity)
    {
        $this->assertInstanceOf(PageLayout::class, $entity);
        $this->assertEquals($expectedData['id'], $entity->getId());
        $this->assertSame($expectedData['identifier'], $entity->getIdentifier());
        $this->assertSame($expectedData['body'], $entity->getBody());

        $this->assertEntityAssets($expectedData, $entity);
    }

    /**
     * @param array      $expectedData
     * @param PageLayout $entity
     */
    protected function assertEntityAssets(array $expectedData, $entity)
    {
        $assets = $entity->getAssets();
        if (!$assets) {
            return;
        }

        $this->assertSame($expectedData['header'], $assets->getHeader());
        $this->assertSame($expectedData['footer'], $assets->getFooter());
        $this->assertSame((array)$expectedData['css_files'], $assets->getCssFiles());
        $this->assertSame((array)$expectedData['js_files'], $assets->getJsFiles());
    }
}
