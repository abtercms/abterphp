<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm\DataMapper;

use AbterPhp\Framework\Orm\DataMapper\SqlDataMapperTest;
use AbterPhp\Website\Domain\Entities\Page;
use AbterPhp\Website\Orm\DataMappers\PageSqlDataMapper;
use Opulence\Databases\Adapters\Pdo\Connection as Connection;

class PageSqlDataMapperTest extends SqlDataMapperTest
{
    /** @var PageSqlDataMapper */
    protected $sut;

    public function setUp()
    {
        $this->connection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->setMethods(['prepare', 'read', 'lastInsertId'])
            ->getMock();

        $this->sut = new PageSqlDataMapper($this->connection, $this->connection);
    }

    /**
     * @param int      $id
     * @param int|null $layoutId
     *
     * @return Page
     */
    protected function getEntity(int $id = 0, ?int $layoutId = null): Page
    {
        $meta   = new Page\Meta('m1', 'm2', 'm3', 'm4', 'm5', 'm6', 'm7', 'm8');
        $assets = new Page\Assets('foo', 'baz', 'yak', ['zar'], ['boi'], null);

        return new Page($id, 'foo', 'bar', 'baz', 'qux', $layoutId, $meta, $assets);
    }

    public function testAddWithoutLayoutId()
    {
        $nextId = '123';

        $entity = $this->getEntity();
        $meta   = $entity->getMeta();
        $assets = $entity->getAssets();

        $sql    = 'INSERT INTO pages (identifier, title, body, layout, layout_id, meta_description, meta_robots, meta_author, meta_copyright, meta_keywords, meta_og_title, meta_og_image, meta_og_description, header, footer, css_files, js_files) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'; // phpcs:ignore
        $values = [
            [$entity->getIdentifier(), \PDO::PARAM_STR],
            [$entity->getTitle(), \PDO::PARAM_STR],
            [$entity->getBody(), \PDO::PARAM_STR],
            [$entity->getLayout(), \PDO::PARAM_STR],
            [$entity->getLayoutId(), \PDO::PARAM_NULL],
            [$meta->getDescription(), \PDO::PARAM_STR],
            [$meta->getRobots(), \PDO::PARAM_STR],
            [$meta->getAuthor(), \PDO::PARAM_STR],
            [$meta->getCopyright(), \PDO::PARAM_STR],
            [$meta->getKeywords(), \PDO::PARAM_STR],
            [$meta->getOGTitle(), \PDO::PARAM_STR],
            [$meta->getOGImage(), \PDO::PARAM_STR],
            [$meta->getOGDescription(), \PDO::PARAM_STR],
            [$assets->getHeader(), \PDO::PARAM_STR],
            [$assets->getFooter(), \PDO::PARAM_STR],
            [implode("\r\n", $assets->getCssFiles()), \PDO::PARAM_STR],
            [implode("\r\n", $assets->getJsFiles()), \PDO::PARAM_STR],
        ];

        $this->lastInsertId($nextId);
        $this->prepare($sql, $this->createWriteStatement($values));

        $this->sut->add($entity);

        $this->assertSame($nextId, (string)$entity->getId());
    }

    public function testAddWithLayoutId()
    {
        $nextId   = '123';
        $layoutId = 66;

        $entity = $this->getEntity(0, $layoutId);
        $meta   = $entity->getMeta();
        $assets = $entity->getAssets();

        $sql    = 'INSERT INTO pages (identifier, title, body, layout, layout_id, meta_description, meta_robots, meta_author, meta_copyright, meta_keywords, meta_og_title, meta_og_image, meta_og_description, header, footer, css_files, js_files) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'; // phpcs:ignore
        $values = [
            [$entity->getIdentifier(), \PDO::PARAM_STR],
            [$entity->getTitle(), \PDO::PARAM_STR],
            [$entity->getBody(), \PDO::PARAM_STR],
            [$entity->getLayout(), \PDO::PARAM_STR],
            [$entity->getLayoutId(), \PDO::PARAM_INT],
            [$meta->getDescription(), \PDO::PARAM_STR],
            [$meta->getRobots(), \PDO::PARAM_STR],
            [$meta->getAuthor(), \PDO::PARAM_STR],
            [$meta->getCopyright(), \PDO::PARAM_STR],
            [$meta->getKeywords(), \PDO::PARAM_STR],
            [$meta->getOGTitle(), \PDO::PARAM_STR],
            [$meta->getOGImage(), \PDO::PARAM_STR],
            [$meta->getOGDescription(), \PDO::PARAM_STR],
            [$assets->getHeader(), \PDO::PARAM_STR],
            [$assets->getFooter(), \PDO::PARAM_STR],
            [implode("\r\n", $assets->getCssFiles()), \PDO::PARAM_STR],
            [implode("\r\n", $assets->getJsFiles()), \PDO::PARAM_STR],
        ];

        $this->lastInsertId($nextId);
        $this->prepare($sql, $this->createWriteStatement($values));

        $this->sut->add($entity);

        $this->assertSame($nextId, (string)$entity->getId());
    }

    public function testDelete()
    {
        $id     = 123;
        $entity = $this->getEntity($id);

        $sql    = 'UPDATE pages AS pages SET deleted = ? WHERE (id = ?)'; // phpcs:ignore
        $values = [[1, \PDO::PARAM_INT], [$entity->getId(), \PDO::PARAM_INT]];

        $this->prepare($sql, $this->createWriteStatement($values));

        $this->sut->delete($entity);
    }

    public function testGetAll()
    {
        $id       = 123;
        $entity   = $this->getEntity($id);
        $layoutId = null;

        $sql          = 'SELECT pages.id, pages.identifier, pages.title, pages.layout_id FROM pages WHERE (pages.deleted = 0)'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'         => $entity->getId(),
                'identifier' => $entity->getIdentifier(),
                'title'      => $entity->getTitle(),
                'layout_id'  => $entity->getLayoutId(),
            ],
        ];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getAll();

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetById()
    {
        $id     = 66;
        $entity = $this->getEntity($id);
        $meta   = $entity->getMeta();
        $assets = $entity->getAssets();

        $sql          = 'SELECT pages.id, pages.identifier, pages.title, pages.body, pages.layout_id, pages.layout, pages.meta_description, pages.meta_robots, pages.meta_author, pages.meta_copyright, pages.meta_keywords, pages.meta_og_title, pages.meta_og_image, pages.meta_og_description, pages.header, pages.footer, pages.css_files, pages.js_files FROM pages WHERE (pages.deleted = 0) AND (pages.id = :page_id)'; // phpcs:ignore
        $values       = ['page_id' => [$id, \PDO::PARAM_INT]];
        $expectedData = [
            [
                'id'                  => $entity->getId(),
                'identifier'          => $entity->getIdentifier(),
                'title'               => $entity->getTitle(),
                'body'                => $entity->getBody(),
                'layout'              => $entity->getLayout(),
                'layout_id'           => $entity->getLayoutId(),
                'meta_description'    => $meta->getDescription(),
                'meta_robots'         => $meta->getRobots(),
                'meta_author'         => $meta->getAuthor(),
                'meta_copyright'      => $meta->getCopyright(),
                'meta_keywords'       => $meta->getKeywords(),
                'meta_og_title'       => $meta->getOGTitle(),
                'meta_og_image'       => $meta->getOGImage(),
                'meta_og_description' => $meta->getOGDescription(),
                'header'              => $assets->getHeader(),
                'footer'              => $assets->getFooter(),
                'css_files'           => implode("\r\n", $assets->getCssFiles()),
                'js_files'            => implode("\r\n", $assets->getJsFiles()),
            ],
        ];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getById($id);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testGetByIdentifier()
    {
        $id     = 66;
        $entity = $this->getEntity($id);
        $meta   = $entity->getMeta();
        $assets = $entity->getAssets();

        $sql          = 'SELECT pages.id, pages.identifier, pages.title, pages.body, pages.layout_id, pages.layout, pages.meta_description, pages.meta_robots, pages.meta_author, pages.meta_copyright, pages.meta_keywords, pages.meta_og_title, pages.meta_og_image, pages.meta_og_description, pages.header, pages.footer, pages.css_files, pages.js_files FROM pages WHERE (pages.deleted = 0) AND (pages.identifier = :identifier)'; // phpcs:ignore
        $values       = ['identifier' => [$entity->getIdentifier(), \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'                  => $entity->getId(),
                'identifier'          => $entity->getIdentifier(),
                'title'               => $entity->getTitle(),
                'body'                => $entity->getBody(),
                'layout'              => $entity->getLayout(),
                'layout_id'           => $entity->getLayoutId(),
                'meta_description'    => $meta->getDescription(),
                'meta_robots'         => $meta->getRobots(),
                'meta_author'         => $meta->getAuthor(),
                'meta_copyright'      => $meta->getCopyright(),
                'meta_keywords'       => $meta->getKeywords(),
                'meta_og_title'       => $meta->getOGTitle(),
                'meta_og_image'       => $meta->getOGImage(),
                'meta_og_description' => $meta->getOGDescription(),
                'header'              => $assets->getHeader(),
                'footer'              => $assets->getFooter(),
                'css_files'           => implode("\r\n", $assets->getCssFiles()),
                'js_files'            => implode("\r\n", $assets->getJsFiles()),
            ],
        ];

        $this->prepare($sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getByIdentifier($entity->getIdentifier());

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testUpdateWithoutLayoutId()
    {
        $id     = 66;
        $entity = $this->getEntity($id);
        $meta   = $entity->getMeta();
        $assets = $entity->getAssets();

        $sql    = 'UPDATE pages AS pages SET identifier = ?, title = ?, body = ?, layout = ?, layout_id = ?, meta_description = ?, meta_robots = ?, meta_author = ?, meta_copyright = ?, meta_keywords = ?, meta_og_title = ?, meta_og_image = ?, meta_og_description = ?, header = ?, footer = ?, css_files = ?, js_files = ? WHERE (id = ?) AND (deleted = 0)'; // phpcs:ignore
        $values = [
            [$entity->getIdentifier(), \PDO::PARAM_STR],
            [$entity->getTitle(), \PDO::PARAM_STR],
            [$entity->getBody(), \PDO::PARAM_STR],
            [$entity->getLayout(), \PDO::PARAM_STR],
            [$entity->getLayoutId(), \PDO::PARAM_NULL],
            [$meta->getDescription(), \PDO::PARAM_STR],
            [$meta->getRobots(), \PDO::PARAM_STR],
            [$meta->getAuthor(), \PDO::PARAM_STR],
            [$meta->getCopyright(), \PDO::PARAM_STR],
            [$meta->getKeywords(), \PDO::PARAM_STR],
            [$meta->getOGTitle(), \PDO::PARAM_STR],
            [$meta->getOGImage(), \PDO::PARAM_STR],
            [$meta->getOGDescription(), \PDO::PARAM_STR],
            [$assets->getHeader(), \PDO::PARAM_STR],
            [$assets->getFooter(), \PDO::PARAM_STR],
            [implode("\r\n", $assets->getCssFiles()), \PDO::PARAM_STR],
            [implode("\r\n", $assets->getJsFiles()), \PDO::PARAM_STR],
            [$entity->getId(), \PDO::PARAM_INT],
        ];

        $this->prepare($sql, $this->createWriteStatement($values));

        $this->sut->update($entity);
    }

    public function testUpdateWithLayoutId()
    {
        $id       = 66;
        $layoutId = 97;
        $entity   = $this->getEntity($id, $layoutId);
        $meta     = $entity->getMeta();
        $assets = $entity->getAssets();

        $sql    = 'UPDATE pages AS pages SET identifier = ?, title = ?, body = ?, layout = ?, layout_id = ?, meta_description = ?, meta_robots = ?, meta_author = ?, meta_copyright = ?, meta_keywords = ?, meta_og_title = ?, meta_og_image = ?, meta_og_description = ?, header = ?, footer = ?, css_files = ?, js_files = ? WHERE (id = ?) AND (deleted = 0)'; // phpcs:ignore
        $values = [
            [$entity->getIdentifier(), \PDO::PARAM_STR],
            [$entity->getTitle(), \PDO::PARAM_STR],
            [$entity->getBody(), \PDO::PARAM_STR],
            [$entity->getLayout(), \PDO::PARAM_STR],
            [$entity->getLayoutId(), \PDO::PARAM_INT],
            [$meta->getDescription(), \PDO::PARAM_STR],
            [$meta->getRobots(), \PDO::PARAM_STR],
            [$meta->getAuthor(), \PDO::PARAM_STR],
            [$meta->getCopyright(), \PDO::PARAM_STR],
            [$meta->getKeywords(), \PDO::PARAM_STR],
            [$meta->getOGTitle(), \PDO::PARAM_STR],
            [$meta->getOGImage(), \PDO::PARAM_STR],
            [$meta->getOGDescription(), \PDO::PARAM_STR],
            [$assets->getHeader(), \PDO::PARAM_STR],
            [$assets->getFooter(), \PDO::PARAM_STR],
            [implode("\r\n", $assets->getCssFiles()), \PDO::PARAM_STR],
            [implode("\r\n", $assets->getJsFiles()), \PDO::PARAM_STR],
            [$entity->getId(), \PDO::PARAM_INT],
        ];

        $this->prepare($sql, $this->createWriteStatement($values));

        $this->sut->update($entity);
    }

    /**
     * @param array $expectedData
     * @param Page  $entity
     */
    protected function assertEntity(array $expectedData, $entity)
    {
        $this->assertInstanceOf(Page::class, $entity);
        $this->assertEquals($expectedData['id'], $entity->getId());
        $this->assertSame($expectedData['identifier'], $entity->getIdentifier());
        $this->assertSame($expectedData['title'], $entity->getTitle());
        $this->assertSame($expectedData['layout_id'], $entity->getLayoutId());

        $this->assertEntityAssets($expectedData, $entity);
    }

    /**
     * @param array $expectedData
     * @param Page  $entity
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
