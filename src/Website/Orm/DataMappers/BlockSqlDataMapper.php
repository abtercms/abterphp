<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm\DataMappers;

use AbterPhp\Website\Domain\Entities\Block as Entity;
use Opulence\Orm\DataMappers\SqlDataMapper;
use Opulence\QueryBuilders\Conditions\ConditionFactory;
use Opulence\QueryBuilders\MySql\QueryBuilder;
use Opulence\QueryBuilders\MySql\SelectQuery;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class BlockSqlDataMapper extends SqlDataMapper implements IBlockDataMapper
{
    /**
     * @param Entity $entity
     */
    public function add($entity)
    {
        if (!$entity instanceof Entity) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a Block entity.');
        }

        $layoutIdType = \PDO::PARAM_NULL;
        if ($entity->getLayoutId()) {
            $layoutIdType = \PDO::PARAM_INT;
        }
        $query = (new QueryBuilder())
            ->insert(
                'blocks',
                [
                    'identifier' => [$entity->getIdentifier(), \PDO::PARAM_STR],
                    'title'      => [$entity->getTitle(), \PDO::PARAM_STR],
                    'body'       => [$entity->getBody(), \PDO::PARAM_STR],
                    'layout'     => [$entity->getLayout(), \PDO::PARAM_STR],
                    'layout_id'  => [$entity->getLayoutId(), $layoutIdType],
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
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a Block entity.');
        }

        $query = (new QueryBuilder())
            ->update('blocks', 'blocks', ['deleted' => [1, \PDO::PARAM_INT]])
            ->where('id = ?')
            ->addUnnamedPlaceholderValue($entity->getId(), \PDO::PARAM_INT);

        $statement = $this->writeConnection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();
    }

    /**
     * @return Entity[]
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
        $query = $this->getBaseQuery()->andWhere('blocks.id = :block_id');

        $parameters = [
            'block_id' => [$id, \PDO::PARAM_INT],
        ];

        $sql = $query->getSql();

        return $this->read($sql, $parameters, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param string $title
     *
     * @return Entity|null
     */
    public function getByIdentifier(string $identifier): ?Entity
    {
        $query = $this->getBaseQuery()->andWhere('blocks.identifier = :identifier');

        $parameters = [
            'identifier' => [$identifier, \PDO::PARAM_STR],
        ];

        return $this->read($query->getSql(), $parameters, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param array $identifiers
     *
     * @return Entity[]
     */
    public function getWithLayoutByIdentifiers(array $identifiers): array
    {
        if (count($identifiers) === 0) {
            return null;
        }

        $conditions = new ConditionFactory();
        $query      = $this->getWithLayoutQuery()
            ->andWhere($conditions->in('blocks.identifier', $identifiers));

        return $this->read($query->getSql(), $query->getParameters(), self::VALUE_TYPE_ARRAY);
    }

    /**
     * @param Entity $entity
     */
    public function update($entity)
    {
        if (!$entity instanceof Entity) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a Block entity.');
        }

        $layoutIdType = \PDO::PARAM_NULL;
        if ($entity->getLayoutId()) {
            $layoutIdType = \PDO::PARAM_INT;
        }

        $query = (new QueryBuilder())
            ->update(
                'blocks',
                'blocks',
                [
                    'identifier' => [$entity->getIdentifier(), \PDO::PARAM_STR],
                    'title'      => [$entity->getTitle(), \PDO::PARAM_STR],
                    'body'       => [$entity->getBody(), \PDO::PARAM_STR],
                    'layout'     => [$entity->getLayout(), \PDO::PARAM_STR],
                    'layout_id'  => [$entity->getLayoutId(), $layoutIdType],
                ]
            )
            ->where('id = ?')
            ->andWhere('deleted = 0')
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
        $layoutId = null;
        if ($hash['layout_id']) {
            $layoutId = (int)$hash['layout_id'];
        }

        return new Entity(
            (int)$hash['id'],
            $hash['identifier'],
            $hash['title'],
            $hash['body'],
            $hash['layout'],
            $layoutId
        );
    }

    /**
     * @return SelectQuery
     */
    private function getBaseQuery(): SelectQuery
    {
        /** @var SelectQuery $query */
        $query = (new QueryBuilder())
            ->select(
                'blocks.id',
                'blocks.identifier',
                'blocks.title',
                'blocks.body',
                'blocks.layout_id',
                'blocks.layout'
            )
            ->from('blocks')
            ->where('blocks.deleted = 0');

        return $query;
    }

    /**
     * @return SelectQuery
     */
    private function getWithLayoutQuery(): SelectQuery
    {
        /** @var SelectQuery $query */
        $query = (new QueryBuilder())
            ->select(
                'blocks.id',
                'blocks.identifier',
                'blocks.title',
                'blocks.body',
                'blocks.layout_id',
                'COALESCE(layouts.body, blocks.layout) AS layout'
            )
            ->from('blocks')
            ->leftJoin('block_layouts', 'layouts', 'layouts.id = blocks.layout_id')
            ->where('blocks.deleted = 0')
            ->andWhere('layouts.deleted = 0 OR layouts.deleted IS NULL');

        return $query;
    }
}
