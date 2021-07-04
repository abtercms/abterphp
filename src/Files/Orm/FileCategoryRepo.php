<?php

declare(strict_types=1);

namespace AbterPhp\Files\Orm;

use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Files\Domain\Entities\FileCategory as Entity;
use AbterPhp\Framework\Orm\GridRepo;
use QB\Generic\Clause\Column;
use QB\Generic\Clause\Table;
use QB\Generic\Expr\Expr;
use QB\Generic\Statement\ISelect;
use QB\MySQL\QueryBuilder\QueryBuilder;
use QB\MySQL\Statement\Select;

class FileCategoryRepo extends GridRepo
{
    protected const USER_GROUP_IDS = 'user_group_ids';

    /** @var QueryBuilder */
    protected $queryBuilder;

    /**
     * @param UserGroup $userGroup
     *
     * @return Entity[]
     */
    public function getByUserGroup(UserGroup $userGroup): array
    {
        $query = $this->joinUserGroups($this->getBaseQuery())
            ->where(new Expr('ugfc2.user_group_id = ?', [$userGroup->getId()]));

        $rows = $this->writer->fetchAll($query);

        return $this->createCollection($rows);
    }

    /**
     * @param array $row
     *
     * @return Entity
     */
    public function createEntity(array $row): Entity
    {
        $userGroups = $this->getUserGroups($row);

        return new Entity(
            $row['id'],
            $row['identifier'],
            $row['name'],
            (bool)$row['is_public'],
            $userGroups
        );
    }

    /**
     * @param array $hash
     *
     * @return array
     */
    private function getUserGroups(array $hash): array
    {
        if (empty($hash[static::USER_GROUP_IDS])) {
            return [];
        }

        $userGroups = [];
        foreach (explode(',', $hash[static::USER_GROUP_IDS]) as $id) {
            $userGroups[] = new UserGroup($id, '', '');
        }

        return $userGroups;
    }

    /**
     * @return Select
     */
    public function getBaseQuery(): Select
    {
        return $this->queryBuilder
            ->select()
            ->from(new Table('file_categories', 'fc'))
            ->columns(
                'fc.id',
                'fc.identifier',
                'fc.name',
                'fc.is_public',
                new Column('GROUP_CONCAT(ugfc.user_group_id)', 'user_group_ids')
            )
            ->leftJoin('user_groups_file_categories', 'ugfc.file_category_id = fc.id', 'ugfc')
            ->where('fc.deleted_at IS NULL')
            ->groupBy('fc.id');
    }

    /**
     * @param Select $query
     *
     * @return Select
     */
    private function joinUserGroups(Select $query): Select
    {
        $query->innerJoin(
            new Table('user_groups_file_categories', 'ugfc2'),
            'fc.id = ugfc2.file_category_id',
        );

        return $query;
    }

    /**
     * @return array<string,string>
     */
    public function getDefaultSorting(): array
    {
        return [
            'fc.name' => ISelect::DIRECTION_ASC,
        ];
    }
}
