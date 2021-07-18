<?php

declare(strict_types=1);

namespace AbterPhp\Files\Orm;

use AbterPhp\Admin\Domain\Entities\User;
use AbterPhp\Admin\Domain\Entities\UserLanguage;
use AbterPhp\Files\Domain\Entities\File;
use AbterPhp\Files\Domain\Entities\FileDownload as Entity;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\Orm\GridRepo;
use DateTime;
use Exception;
use QB\Generic\Clause\Column;
use QB\Generic\Expr\Expr;
use QB\Generic\Statement\ISelect;
use QB\MySQL\QueryBuilder\QueryBuilder;
use QB\MySQL\Statement\Select;

class FileDownloadRepo extends GridRepo
{
    /** @var QueryBuilder */
    protected $queryBuilder;

    protected string $tableName = 'file_downloads';

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
     * @param File $file
     *
     * @return Entity[]
     */
    public function getByFile(File $file): array
    {
        return $this->getPage(0, static::DEFAULT_LIMIT, [], [new Expr('file_id = ?', [$file->getId()])]);
    }

    /**
     * @param User $user
     *
     * @return Entity[]
     */
    public function getByUser(User $user): array
    {
        return $this->getPage(0, static::DEFAULT_LIMIT, [], [new Expr('user_id = ?', [$user->getId()])]);
    }

    /**
     * @param array $row
     *
     * @return Entity
     * @throws Exception
     */
    public function createEntity(array $row): Entity
    {
        $file         = new File($row['file_id'], $row['filesystem_name'], $row['public_name'], $row['mime'], '');
        $userLanguage = new UserLanguage('', '', '');
        $user         = new User(
            $row['user_id'],
            $row['username'],
            '',
            '',
            true,
            true,
            $userLanguage
        );

        return new Entity(
            $row['id'],
            $file,
            $user,
            new DateTime($row['downloaded_at'])
        );
    }

    /**
     * @return Select
     */
    public function getBaseQuery(): Select
    {
        return $this->queryBuilder
            ->select()
            ->from('file_downloads')
            ->columns(
                'file_downloads.id',
                'file_downloads.file_id',
                'file_downloads.user_id',
                'file_downloads.downloaded_at',
                new Column('files.filesystem_name', 'filesystem_name'),
                new Column('files.public_name', 'public_name'),
                new Column('files.mime', 'mime'),
                new Column('users.username', 'username')
            )
            ->innerJoin(
                'files',
                'files.id=file_downloads.file_id'
            )
            ->innerJoin(
                'users',
                'users.id=file_downloads.user_id'
            )
            ->where('file_downloads.deleted_at IS NULL');
    }

    /**
     * @return array<string,string>
     */
    public function getDefaultSorting(): array
    {
        return [
            'downloaded_at' => ISelect::DIRECTION_DESC,
        ];
    }
}
