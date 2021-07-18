<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm;

use AbterPhp\Framework\Orm\GridRepo;
use AbterPhp\Website\Domain\Entities\BlockLayout as Entity;
use QB\Generic\Statement\ISelect;
use QB\MySQL\QueryBuilder\QueryBuilder;

class BlockLayoutRepo extends GridRepo
{
    /** @var QueryBuilder */
    protected $queryBuilder;

    protected string $tableName = 'block_layouts';

    protected ?string $deletedAtColumn = self::COLUMN_DELETED_AT;

    /**
     * @param string $identifier
     *
     * @return Entity|null
     */
    public function getByIdentifier(string $identifier): ?Entity
    {
        $entity = $this->getOne(['identifier' => $identifier]);

        assert($entity === null || $entity instanceof Entity);

        return $entity;
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
            $row['body']
        );
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
}
