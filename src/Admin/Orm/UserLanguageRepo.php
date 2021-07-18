<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Orm;

use AbterPhp\Admin\Domain\Entities\UserLanguage as Entity;
use AbterPhp\Framework\Orm\GridRepo;
use InvalidArgumentException;
use Opulence\Orm\IEntity;
use QB\Generic\Statement\ISelect;
use QB\MySQL\QueryBuilder\QueryBuilder;

class UserLanguageRepo extends GridRepo
{
    /** @var QueryBuilder */
    protected $queryBuilder;

    protected string $tableName = 'user_languages';

    protected ?string $deletedAtColumn = self::COLUMN_DELETED_AT;

    /**
     * @param IEntity $entity
     */
    public function add(IEntity $entity)
    {
        assert($entity instanceof Entity, new InvalidArgumentException());

        parent::add($entity);
    }

    /**
     * @param Entity $entity
     */
    public function update(IEntity $entity)
    {
        assert($entity instanceof Entity, new InvalidArgumentException());

        parent::update($entity);
    }

    /**
     * @param Entity $entity
     */
    public function delete(IEntity $entity)
    {
        assert($entity instanceof Entity, new InvalidArgumentException());

        parent::delete($entity);
    }

    /**
     * @param string $identifier
     *
     * @return Entity|null
     */
    public function getByIdentifier(string $identifier): ?Entity
    {
        $entity = parent::getOne(['identifier' => $identifier]);

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
            $row['identifier'],
            $row['name']
        );
    }

    /**
     * @return array<string,string>
     */
    public function getDefaultSorting(): array
    {
        return [
            'name' => ISelect::DIRECTION_ASC
        ];
    }
}
