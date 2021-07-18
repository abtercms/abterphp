<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Orm;

use AbterPhp\Contact\Domain\Entities\Form as Entity;
use AbterPhp\Framework\Orm\GridRepo;
use QB\Generic\Statement\ISelect;
use QB\MySQL\QueryBuilder\QueryBuilder;
use QB\MySQL\Statement\Select;

class FormRepo extends GridRepo
{
    /** @var QueryBuilder */
    protected $queryBuilder;

    protected string $tableName = 'forms';

    protected ?string $deletedAtColumn = self::COLUMN_DELETED_AT;

    /**
     * @param string $identifier
     *
     * @return Entity|null
     */
    public function getByIdentifier(string $identifier): ?Entity
    {
        $entity = $this->getOne(['cf.identifier' => $identifier]);

        assert($entity === null || $entity instanceof Entity);

        return $entity;
    }

    /**
     * @return array<string,string>
     */
    public function getDefaultSorting(): array
    {
        return [
            'cf.name' => ISelect::DIRECTION_ASC,
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
            $row['to_name'],
            $row['to_email'],
            $row['success_url'],
            $row['failure_url'],
            (int)$row['max_body_length']
        );
    }

    /**
     * @return Select
     */
    public function getBaseQuery(): Select
    {
        return $this->queryBuilder
            ->select(
                'cf.id',
                'cf.name',
                'cf.identifier',
                'cf.to_name',
                'cf.to_email',
                'cf.success_url',
                'cf.failure_url',
                'cf.max_body_length'
            )
            ->from('contact_forms', 'cf')
            ->where('cf.deleted_at IS NULL');
    }
}
