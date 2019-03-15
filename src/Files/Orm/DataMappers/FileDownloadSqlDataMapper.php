<?php

declare(strict_types=1);

namespace AbterPhp\Files\Orm\DataMappers;

use AbterPhp\Admin\Domain\Entities\User;
use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Admin\Domain\Entities\UserLanguage;
use AbterPhp\Files\Domain\Entities\File;
use AbterPhp\Files\Domain\Entities\FileDownload as Entity;
use Opulence\Orm\DataMappers\SqlDataMapper;
use Opulence\QueryBuilders\MySql\QueryBuilder;
use Opulence\QueryBuilders\MySql\SelectQuery;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class FileDownloadSqlDataMapper extends SqlDataMapper implements IFileDownloadDataMapper
{
    /**
     * @param Entity $entity
     */
    public function add($entity)
    {
        if (!$entity instanceof Entity) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a FileDownload entity.');
        }

        $query = (new QueryBuilder())
            ->insert(
                'file_downloads',
                [
                    'file_id'       => [$entity->getFile()->getId(), \PDO::PARAM_INT],
                    'user_id'       => [$entity->getUser()->getId(), \PDO::PARAM_INT],
                    'downloaded_at' => [$entity->getDownloadedAt()->format(Entity::DATE_FORMAT), \PDO::PARAM_STR],
                ]
            );

        $statement = $this->writeConnection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();

        $entity->setId($this->writeConnection->lastInsertId());
    }

    /**
     * @param Entity $entity
     */
    public function delete($entity)
    {
        if (!$entity instanceof Entity) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a FileDownload entity.');
        }

        $query = (new QueryBuilder())
            ->update('file_downloads', 'file_downloads', ['deleted' => [1, \PDO::PARAM_INT]])
            ->where('id = ?')
            ->addUnnamedPlaceholderValue($entity->getId(), \PDO::PARAM_INT);

        $statement = $this->writeConnection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $query = $this->getBaseQuery();

        return $this->read($query->getSql(), [], self::VALUE_TYPE_ARRAY);
    }

    /**
     * @param int      $limitFrom
     * @param int      $pageSize
     * @param string[] $orders
     * @param array    $conditions
     * @param array    $params
     *
     * @return Entity[]
     */
    public function getPage(int $limitFrom, int $pageSize, array $orders, array $conditions, array $params): array
    {
        $query = $this->getBaseQuery()
            ->limit($pageSize)
            ->offset($limitFrom);

        foreach ($orders as $order) {
            $query->addOrderBy($order);
        }

        foreach ($conditions as $condition) {
            $query->andWhere($condition);
        }

        $replaceCount = 1;

        $sql = $query->getSql();
        $sql = str_replace('SELECT', 'SELECT SQL_CALC_FOUND_ROWS', $sql, $replaceCount);

        return $this->read($sql, $params, self::VALUE_TYPE_ARRAY);
    }

    /**
     * @param int|string $id
     *
     * @return Entity|null
     */
    public function getById($id)
    {
        $query = $this->getBaseQuery()->andWhere('file_downloads.id = :file_download_id');

        $parameters = [
            'file_download_id' => [$id, \PDO::PARAM_INT],
        ];

        return $this->read($query->getSql(), $parameters, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param int $userId
     *
     * @return Entity[]
     */
    public function getByUserId(int $userId): array
    {
        $query      = $this->getBaseQuery()->andWhere('user_id = :user_id');
        $parameters = [
            'user_id' => [$userId, \PDO::PARAM_INT],
        ];

        return $this->read($query->getSql(), $parameters, self::VALUE_TYPE_ARRAY);
    }

    /**
     * @param int $fileId
     *
     * @return Entity[]
     */
    public function getByFileId(int $fileId): array
    {
        $query = $this->getBaseQuery()->andWhere('file_id = :file_id');

        $parameters = [
            'file_id' => [$fileId, \PDO::PARAM_INT],
        ];

        return $this->read($query->getSql(), $parameters, self::VALUE_TYPE_ARRAY);
    }

    /**
     * @param Entity $entity
     */
    public function update($entity)
    {
        if (!$entity instanceof Entity) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a FileDownload entity.');
        }

        $query = (new QueryBuilder())
            ->update(
                'file_downloads',
                'file_downloads',
                [
                    'file_id'       => [$entity->getFile()->getId(), \PDO::PARAM_INT],
                    'user_id'       => [$entity->getUser()->getId(), \PDO::PARAM_INT],
                    'downloaded_at' => [$entity->getDownloadedAt()->format(Entity::DATE_FORMAT), \PDO::PARAM_STR],
                ]
            )
            ->where('id = ?')
            ->addUnnamedPlaceholderValue($entity->getId(), \PDO::PARAM_INT);

        $statement = $this->writeConnection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();
    }

    /**
     * @param array $hash
     *
     * @return Entity
     */
    protected function loadEntity(array $hash)
    {
        $file         = new File((int)$hash['file_id'], $hash['filesystem_name'], $hash['public_name'], '');
        $userGroup    = new UserGroup(0, '', '');
        $userLanguage = new UserLanguage(0, '', '');
        $user         = new User(
            (int)$hash['user_id'],
            $hash['username'],
            '',
            '',
            $userGroup,
            $userLanguage,
            true,
            true
        );

        return new Entity(
            (int)$hash['id'],
            $file,
            $user,
            new \DateTime((string)$hash['downloaded_at'])
        );
    }

    /**
     * @return SelectQuery
     */
    private function getBaseQuery()
    {
        /** @var SelectQuery $query */
        $query = (new QueryBuilder())
            ->select(
                'file_downloads.id',
                'file_downloads.file_id',
                'file_downloads.user_id',
                'file_downloads.downloaded_at',
                'files.filesystem_name AS filesystem_name',
                'files.public_name AS public_name',
                'users.username AS username'
            )
            ->from('file_downloads')
            ->innerJoin(
                'files',
                'files',
                'files.id=file_downloads.file_id'
            )
            ->innerJoin(
                'users',
                'users',
                'users.id=file_downloads.user_id'
            )
            ->where('file_downloads.deleted = 0');

        return $query;
    }
}
