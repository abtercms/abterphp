<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm;

use AbterPhp\Framework\Orm\GridRepo;
use AbterPhp\Website\Domain\Entities\ContentListItem as Entity;
use QB\Generic\Statement\ISelect;
use QB\MySQL\QueryBuilder\QueryBuilder;
use QB\MySQL\Statement\Select;

class ContentListItemRepo extends GridRepo
{
    /** @var QueryBuilder */
    protected $queryBuilder;

    protected string $tableName = 'content_list_items';

    protected ?string $deletedAtColumn = self::COLUMN_DELETED_AT;

    /**
     * @param string $listId
     *
     * @return Entity[]
     */
    public function getByListId(string $listId): array
    {
        return $this->getPage(0, static::DEFAULT_LIMIT, [], ['list_items.list_id' => $listId]);
    }

    /**
     * @param string[] $identifiers
     *
     * @return Entity[]
     */
    public function getByListIds(array $identifiers): array
    {
        return $this->getPage(0, static::DEFAULT_LIMIT, [], ['list_items.list_id' => $identifiers]);
    }

    /**
     * @param array $row
     *
     * @return Entity
     */
    public function createEntity(array $row): Entity
    {
        return new Entity(
            $row['id'],
            $row['list_id'],
            $row['label'],
            $row['label_href'],
            $row['content'],
            $row['content_href'],
            $row['img_src'],
            $row['img_alt'],
            $row['img_href'],
            $row['classes']
        );
    }

    /**
     * @return Select
     */
    public function getBaseQuery(): Select
    {
        return $this->queryBuilder
            ->select(
                'list_items.id',
                'list_items.list_id',
                'list_items.label',
                'list_items.label_href',
                'list_items.content',
                'list_items.content_href',
                'list_items.img_src',
                'list_items.img_alt',
                'list_items.img_href',
                'list_items.classes'
            )
            ->from('list_items')
            ->where('list_items.deleted_at IS NULL');
    }

    /**
     * @return array<string,string>
     */
    public function getDefaultSorting(): array
    {
        return [
            'label' => ISelect::DIRECTION_ASC,
        ];
    }
}
