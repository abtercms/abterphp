<?php

declare(strict_types=1);

namespace AbterPhp\Files\Orm;

use AbterPhp\Admin\Domain\Entities\User;
use AbterPhp\Files\Domain\Entities\File as Entity;
use AbterPhp\Files\Domain\Entities\FileCategory;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\Orm\GridRepo;
use DateTime;
use Exception;
use QB\Generic\Clause\Column;
use QB\Generic\Clause\Table;
use QB\Generic\Expr\Expr;
use QB\Generic\Statement\ISelect;
use QB\MySQL\QueryBuilder\QueryBuilder;
use QB\MySQL\Statement\Select;

class FileRepo extends GridRepo
{
    /** @var QueryBuilder */
    protected $queryBuilder;

    protected string $tableName = 'files';

    protected ?string $deletedAtColumn = self::COLUMN_DELETED_AT;

    /**
     * @param Entity $entity
     */
    public function add(IStringerEntity $entity)
    {
        assert($entity instanceof Entity);

        parent::add($entity);
    }

    /**
     * @param Entity $entity
     */
    public function update(IStringerEntity $entity)
    {
        assert($entity instanceof Entity);

        parent::update($entity);
    }

    /**
     * @param Entity $entity
     */
    public function delete(IStringerEntity $entity)
    {
        assert($entity instanceof Entity);

        parent::delete($entity);
    }

    /**
     * @param User $user
     *
     * @return Entity[]
     */
    public function getByUser(User $user): array
    {
        $query = $this
            ->withUserGroup($this->getBaseQuery())
            ->where(new Expr('user_groups.user_id = ?', [$user->getId()]));

        $rows = $this->writer->fetchAll($query);

        return $this->createCollection($rows);
    }

    /**
     * @param string $filesystemName
     *
     * @return Entity|null
     */
    public function getByFilesystemName(string $filesystemName): ?Entity
    {
        $entity = $this->getOne(
            [
                'filesystem_name' => $filesystemName,
            ]
        );

        assert($entity === null || $entity instanceof Entity);

        return $entity;
    }

    /**
     * @param string $filesystemName
     *
     * @return Entity|null
     */
    public function getPublicByFilesystemName(string $filesystemName): ?Entity
    {
        $entity = $this->getOne(
            [
                'files.filesystem_name' => $filesystemName,
                'file_categories.is_public' => '1'
            ]
        );

        assert($entity === null || $entity instanceof Entity);

        return $entity;
    }

    /**
     * @param string[] $identifiers
     *
     * @return Entity[]
     */
    public function getPublicByCategoryIdentifiers(array $identifiers): array
    {
        if (count($identifiers) === 0) {
            return [];
        }

        $query = $this
            ->withUserGroup($this->getBaseQuery())
            ->where(new Expr('file_categories.identifier IN (?)', [$identifiers]));

        $rows = $this->writer->fetchAll($query);

        return $this->createCollection($rows);
    }

    /**
     * @param array $row
     *
     * @return Entity
     * @throws Exception
     */
    public function createEntity(array $row): Entity
    {
        $category = new FileCategory(
            $row['file_category_id'],
            (string)$row['file_category_identifier'],
            (string)$row['file_category_name'],
            (bool)$row['file_category_name']
        );

        $uploadedAt = new DateTime((string)$row['uploaded_at']);

        return new Entity(
            $row['id'],
            $row['filesystem_name'],
            $row['public_name'],
            $row['mime'],
            $row['description'],
            $category,
            $uploadedAt,
            true
        );
    }

    /**
     * @return Select
     */
    protected function getBaseQuery(): Select
    {
        return $this->queryBuilder
            ->select()
            ->columns(
                'files.id',
                'files.filesystem_name',
                'files.public_name',
                'files.mime',
                'files.file_category_id',
                'files.description',
                'files.uploaded_at',
                new Column('file_categories.name', 'file_category_name'),
                new Column('file_categories.identifier', 'file_category_identifier')
            )
            ->from('files')
            ->innerJoin(
                'file_categories',
                'file_categories.id = files.file_category_id AND file_categories.deleted_at IS NULL'
            )
            ->where('files.deleted_at IS NULL')
            ->groupBy('files.id');
    }

    /**
     * @param Select $query
     *
     * @return Select
     */
    private function withUserGroup(Select $query): Select
    {
        $query
            ->innerJoin(
                new Table('user_groups_file_categories', 'ugfc'),
                'file_categories.id = ugfc.file_category_id AND file_categories.deleted_at IS NULL',
            )
            ->innerJoin(
                'user_groups',
                'user_groups.id = ugfc.user_group_id AND user_groups.deleted_at IS NULL'
            );

        return $query;
    }

    /**
     * @return array<string,string>
     */
    public function getDefaultSorting(): array
    {
        return [
            'files.public_name' => ISelect::DIRECTION_ASC,
        ];
    }
}
