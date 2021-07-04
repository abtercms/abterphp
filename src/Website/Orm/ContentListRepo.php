<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm;

use AbterPhp\Framework\Orm\GridRepo;
use AbterPhp\Website\Domain\Entities\ContentList as Entity;
use Opulence\Orm\IEntity;
use QB\Generic\Statement\ISelect;

class ContentListRepo extends GridRepo
{
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
     * @return IEntity
     */
    public function createEntity(array $row): IEntity
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
