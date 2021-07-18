<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm;

use AbterPhp\Framework\Orm\GridRepo;
use AbterPhp\Website\Domain\Entities\ContentList as Entity;
use QB\Generic\Statement\ISelect;
use QB\MySQL\QueryBuilder\QueryBuilder;

class ContentListRepo extends GridRepo
{
    /** @var QueryBuilder */
    protected $queryBuilder;

    protected string $tableName = 'content_lists';

    protected ?string $deletedAtColumn = self::COLUMN_DELETED_AT;

    /**
     * @param string $identifier
     *
     * @return Entity|null
     * @throws \Opulence\Orm\OrmException
     */
    public function getByIdentifier(string $identifier): ?Entity
    {
        return $this->getOne(['identifier' => $identifier]);
    }

    /**
     * @param string[] $identifiers
     *
     * @return Entity[]
     * @throws \Opulence\Orm\OrmException
     */
    public function getByIdentifiers(array $identifiers): array
    {
        return $this->getPage(0, static::DEFAULT_LIMIT, [], ['lists.identifier' => $identifiers]);
    }

    /**
     * @return array<string,string>
     */
    public function getDefaultSorting(): array
    {
        return [
            'name' => ISelect::DIRECTION_ASC,
        ];
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
            $row['name'],
            $row['identifier'],
            $row['classes'],
            (bool)$row['protected'],
            (bool)$row['with_links'],
            (bool)$row['with_label_links'],
            (bool)$row['with_html'],
            (bool)$row['with_images'],
            (bool)$row['with_classes']
        );
    }
}
