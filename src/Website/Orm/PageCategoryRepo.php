<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm;

use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Framework\Orm\GridRepo;
use AbterPhp\Framework\Orm\IdGeneratorUserTrait;
use AbterPhp\Website\Domain\Entities\PageCategory as Entity;
use Opulence\Orm\IEntity;
use QB\Generic\Clause\Table;
use QB\Generic\Statement\ISelect;
use QB\MySQL\QueryBuilder\QueryBuilder;
use QB\MySQL\Statement\Select;

class PageCategoryRepo extends GridRepo
{
    use IdGeneratorUserTrait;

    protected const USER_GROUP_IDS = 'user_group_ids';

    /** @var QueryBuilder */
    protected $queryBuilder;

    protected string $tableName = 'page_categories';

    protected ?string $deletedAtColumn = self::COLUMN_DELETED_AT;

    /**
     * @param string $identifier
     *
     * @return Entity|null
     */
    public function getByIdentifier(string $identifier): ?Entity
    {
        return $this->getOne(['identifier' => $identifier]);
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

    public function createEntity(array $row): IEntity
    {
        $userGroups = $this->loadUserGroups($row);

        return new Entity(
            $row['id'],
            $row['name'],
            $row['identifier'],
            $userGroups
        );
    }

    /**
     * @param array $row
     *
     * @return array
     */
    private function loadUserGroups(array $row): array
    {
        if (empty($row[static::USER_GROUP_IDS])) {
            return [];
        }

        $userGroups = [];
        foreach (explode(',', $row[static::USER_GROUP_IDS]) as $id) {
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
            ->select(
                'pc.id',
                'pc.name',
                'pc.identifier',
                'GROUP_CONCAT(ugpc.user_group_id) AS user_group_ids'
            )
            ->from('page_categories', 'pc')
            ->leftJoin(new Table('user_groups_page_categories', 'ugpc'), 'ugpc.page_category_id = pc.id')
            ->where('pc.deleted_at IS NULL')
            ->groupBy('pc.id');
    }
}
