<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm;

use AbterPhp\Framework\Orm\GridRepo;
use AbterPhp\Website\Domain\Entities\Block as Entity;
use QB\Generic\Clause\Column;
use QB\Generic\Clause\Table;
use QB\Generic\Expr\Expr;
use QB\Generic\Statement\ISelect;
use QB\MySQL\QueryBuilder\QueryBuilder;
use QB\MySQL\Statement\Select;

class BlockRepo extends GridRepo
{
    /** @var QueryBuilder */
    protected $queryBuilder;

    protected string $tableName = 'blocks';

    protected ?string $deletedAtColumn = self::COLUMN_DELETED_AT;

    /**
     * @param string $identifier
     *
     * @return Entity|null
     */
    public function getByIdentifier(string $identifier): ?Entity
    {
        $entity = $this->getOne(['blocks.identifier' => $identifier]);

        assert($entity === null || $entity instanceof Entity);

        return $entity;
    }

    /**
     * @param string[] $identifiers
     *
     * @return Entity[]
     */
    public function getWithLayoutByIdentifiers(array $identifiers): array
    {
        if (count($identifiers) === 0) {
            return [];
        }

        $query = $this->getWithLayoutQuery()
            ->where(new Expr('blocks.identifier = ?', [$identifiers]));

        $rows = $this->writer->fetchAll($query);
        if (empty($rows)) {
            return [];
        }

        return $this->createCollection($rows);
    }

    /**
     * @param array $row
     *
     * @return Entity
     */
    public function createEntity(array $row): Entity
    {
        $layoutId = $row['layout_id'] ?: null;

        return new Entity(
            $row['id'],
            $row['identifier'],
            $row['title'],
            $row['body'],
            $row['layout'],
            $layoutId
        );
    }

    /**
     * @return Select
     */
    public function getBaseQuery(): Select
    {
        return $this->queryBuilder
            ->select(
                'blocks.id',
                'blocks.identifier',
                'blocks.title',
                'blocks.body',
                'blocks.layout_id',
                'blocks.layout'
            )
            ->from('blocks')
            ->where('blocks.deleted_at IS NULL');
    }

    /**
     * @return Select
     */
    private function getWithLayoutQuery(): Select
    {
        return $this->queryBuilder
            ->select(
                'blocks.id',
                'blocks.identifier',
                'blocks.title',
                'blocks.body',
                'blocks.layout_id',
                'blocks.layout',
                new Column('COALESCE(layouts.body, blocks.layout)', 'layout')
            )
            ->from('blocks')
            ->leftJoin(new Table('block_layouts', 'layouts'), 'layouts.id = blocks.layout_id AND layouts.deleted_at IS NULL')
            ->where('blocks.deleted_at IS NULL');
    }

    /**
     * @return array<string,string>
     */
    public function getDefaultSorting(): array
    {
        return [
            'title' => ISelect::DIRECTION_ASC,
        ];
    }
}
