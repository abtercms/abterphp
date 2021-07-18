<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm;

use AbterPhp\Framework\Orm\GridRepo;
use AbterPhp\Website\Domain\Entities\Page as Entity;
use AbterPhp\Website\Domain\Entities\Page\Assets as PageAssets;
use AbterPhp\Website\Domain\Entities\PageCategory;
use AbterPhp\Website\Domain\Entities\PageLayout\Assets as LayoutAssets;
use QB\Generic\Clause\Column;
use QB\Generic\Clause\Table;
use QB\Generic\Expr\Expr;
use QB\Generic\Statement\ISelect;
use QB\MySQL\QueryBuilder\QueryBuilder;
use QB\MySQL\Statement\Select;

class PageRepo extends GridRepo
{
    /** @var QueryBuilder */
    protected $queryBuilder;

    protected string $tableName = 'pages';

    protected ?string $deletedAtColumn = self::COLUMN_DELETED_AT;

    /**
     * @param string $identifier
     *
     * @return Entity|null
     */
    public function getByIdentifier(string $identifier): ?Entity
    {
        $query = $this->getExtendedQuery()
            ->where(new Expr('pages.identifier = ?', [$identifier]));

        $row = $this->writer->fetch($query);
        if (empty($row)) {
            return null;
        }

        return $this->createEntity($row);
    }

    /**
     * @param string $identifier
     *
     * @return Entity|null
     */
    public function getWithLayout(string $identifier): ?Entity
    {
        $query = $this->getWithLayoutQuery()
            ->where(new Expr('(pages.identifier = ? OR pages.id = ?)', [$identifier, $identifier]));

        $row = $this->writer->fetch($query);
        if (empty($row)) {
            return null;
        }

        return $this->createEntity($row);
    }

    /**
     * @param string[] $identifiers
     *
     * @return Entity[]
     */
    public function getByCategoryIdentifiers(array $identifiers): array
    {
        $query = $this->getSimplifiedQuery()
            ->where(new Expr('page_categories.identifier', [$identifiers]))
            ->where('pages.is_draft = 0');

        $rows = $this->writer->fetchAll($query);
        if (empty($rows)) {
            return [];
        }

        return $this->createCollection($rows);
    }

    /**
     * @return array<string,string>
     */
    public function getDefaultSorting(): array
    {
        return [
            'pages.title' => ISelect::DIRECTION_ASC,
        ];
    }


    public function createEntity(array $row): Entity
    {
        $meta     = $this->loadMeta($row);
        $assets   = $this->loadAssets($row);
        $lede     = $row['lede'] ?? '';
        $body     = $row['body'] ?? '';
        $classes  = $row['classes'] ?? '';
        $category = $this->loadCategory($row);
        $layout   = $row['layout'] ?? '';
        $layoutId = $row['layout_id'] ?? '';

        return new Entity(
            $row['id'],
            $row['identifier'],
            $row['title'],
            $classes,
            $lede,
            $body,
            (bool)$row['is_draft'],
            $category,
            $layout,
            $layoutId,
            $meta,
            $assets
        );
    }

    /**
     * @param array $hash
     *
     * @return Entity\Meta|null
     */
    protected function loadMeta(array $hash): ?Entity\Meta
    {
        if (!array_key_exists('meta_description', $hash)) {
            return null;
        }

        return new Entity\Meta(
            $hash['meta_description'],
            $hash['meta_robots'],
            $hash['meta_author'],
            $hash['meta_copyright'],
            $hash['meta_keywords'],
            $hash['meta_og_title'],
            $hash['meta_og_image'],
            $hash['meta_og_description']
        );
    }

    /**
     * @param array $hash
     *
     * @return PageAssets|null
     */
    protected function loadAssets(array $hash): ?PageAssets
    {
        if (!array_key_exists('css_files', $hash)) {
            return null;
        }

        $layoutAssets = $this->loadLayoutAssets($hash);

        return new PageAssets(
            $hash['identifier'],
            $hash['header'],
            $hash['footer'],
            $this->extractFiles($hash['css_files']),
            $this->extractFiles($hash['js_files']),
            $layoutAssets
        );
    }

    /**
     * @param array $hash
     *
     * @return PageCategory|null
     */
    protected function loadCategory(array $hash): ?PageCategory
    {
        $id         = $hash['category_id'] ?? '';
        $name       = $hash['category_name'] ?? '';
        $identifier = $hash['category_identifier'] ?? '';

        if (!$id && !$name && !$identifier) {
            return null;
        }

        return new PageCategory($id, $name, $identifier);
    }

    /**
     * @param array $hash
     *
     * @return LayoutAssets|null
     */
    protected function loadLayoutAssets(array $hash): ?LayoutAssets
    {
        if (!array_key_exists('layout_css_files', $hash) || null === $hash['layout_css_files']) {
            return null;
        }

        return new LayoutAssets(
            $hash['layout_identifier'],
            $hash['layout_header'],
            $hash['layout_footer'],
            $this->extractFiles($hash['layout_css_files']),
            $this->extractFiles($hash['layout_js_files'])
        );
    }

    /**
     * @param string $rawData
     *
     * @return string[]
     */
    private function extractFiles(string $rawData): array
    {
        if (empty($rawData)) {
            return [];
        }

        return explode("\n", str_replace("\r", "", trim($rawData)));
    }

    /**
     * @return Select
     */
    public function getBaseQuery(): Select
    {
        return $this->queryBuilder
            ->select(
                'pages.id',
                'pages.identifier',
                'pages.title',
                'pages.is_draft',
                'pages.category_id',
                'pages.layout_id'
            )
            ->from('pages')
            ->where('pages.deleted_at IS NULL');
    }

    /**
     * @return Select
     */
    private function getSimplifiedQuery(): Select
    {
        return $this->queryBuilder
            ->select(
                'pages.id',
                'pages.identifier',
                'pages.title',
                'pages.lede',
                'pages.is_draft',
                'page_categories.id AS category_id',
                'page_categories.identifier AS category_identifier',
                'page_categories.name AS category_name'
            )
            ->from('pages')
            ->innerJoin(new Table('page_categories', 'page_categories'), 'page_categories.id = pages.category_id')
            ->where('pages.deleted_at IS NULL');
    }

    /**
     * @return Select
     */
    public function getGridQuery(): Select
    {
        return (new QueryBuilder())
            ->select(
                'pages.id',
                'pages.identifier',
                'pages.title',
                'pages.is_draft',
                'categories.name AS category_name',
                'pages.layout_id',
                "IF(layouts.name <> '', layouts.name, pages.layout) AS layout"
            )
            ->from('pages')
            ->leftJoin(new Table('page_categories', 'categories'), 'categories.id = pages.category_id')
            ->leftJoin(new Table('page_layouts', 'layouts'), 'layouts.id = pages.layout_id')
            ->where('pages.deleted_at IS NULL');
    }

    /**
     * @return Select
     */
    private function getExtendedQuery(): Select
    {
        return $this->queryBuilder
            ->select(
                'pages.id',
                'pages.identifier',
                'pages.title',
                'pages.classes',
                'pages.lede',
                'pages.body',
                'pages.is_draft',
                'pages.category_id',
                'pages.layout_id',
                'pages.layout',
                'pages.meta_description',
                'pages.meta_robots',
                'pages.meta_author',
                'pages.meta_copyright',
                'pages.meta_keywords',
                'pages.meta_og_title',
                'pages.meta_og_image',
                'pages.meta_og_description',
                'pages.header',
                'pages.footer',
                'pages.css_files',
                'pages.js_files'
            )
            ->from('pages')
            ->where('pages.deleted_at IS NULL');
    }

    /**
     * @return Select
     */
    private function getWithLayoutQuery(): Select
    {
        return $this->queryBuilder
            ->select(
                'pages.id',
                'pages.identifier',
                'pages.title',
                new Column("CONCAT(layouts.classes, ' ', pages.classes)", 'classes'),
                'pages.lede',
                'pages.body',
                'pages.is_draft',
                'pages.category_id',
                'pages.layout_id',
                new Column('COALESCE(layouts.body, pages.layout)', 'layout'),
                'pages.meta_description',
                'pages.meta_robots',
                'pages.meta_author',
                'pages.meta_copyright',
                'pages.meta_keywords',
                'pages.meta_og_title',
                'pages.meta_og_image',
                'pages.meta_og_description',
                new Column('pages.header', 'header'),
                new Column('pages.footer', 'footer'),
                new Column('pages.css_file', 'css_files'),
                new Column('pages.js_files', 'js_files'),
                new Column('layouts.identifier', 'layout_identifier'),
                new Column('layouts.header', 'layout_header'),
                new Column('layouts.footer', 'layout_footer'),
                new Column('layouts.css_files', 'layout_css_files'),
                new Column('layouts.js_files', 'layout_js_files'),
            )
            ->from('pages')
            ->leftJoin(
                new Table('page_layouts', 'layouts'),
                'layouts.id = pages.layout_id AND layouts.deleted_at IS NULL'
            )
            ->where('pages.deleted_at IS NULL');
    }
}
