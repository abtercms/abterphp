<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Bootstrappers\Vendor;

use AbterPhp\Framework\Constant\Env;
use AbterPhp\Framework\Database\PDO\Writer;
use AbterPhp\Framework\Environments\Environment;
use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\Bootstrappers\ILazyBootstrapper;
use Opulence\Ioc\IContainer;
use QB\Generic\QueryBuilder\IQueryBuilder;
use QB\Generic\QueryBuilder\QueryBuilder as GenericQueryBuilder;
use QB\MySQL\QueryBuilder\QueryBuilder as MySQLQueryBuilder;
use QB\PostgreSQL\QueryBuilder\QueryBuilder as PostgreSQLQueryBuilder;

class QBQueryBuilderBootstrapper extends Bootstrapper implements ILazyBootstrapper
{
    /**
     * @inheritdoc
     */
    public function getBindings(): array
    {
        return [
            IQueryBuilder::class,
        ];
    }

    /**
     * @param IContainer $container
     */
    public function registerBindings(IContainer $container): void
    {
        $factory = $this->createQueryBuilder(Environment::mustGetVar(Env::PDO_WRITE_TYPE));

        $container->bindInstance(IQueryBuilder::class, $factory);
    }

    /**
     * @param string $dialect
     *
     * @return IQueryBuilder
     */
    protected function createQueryBuilder(string $dialect): IQueryBuilder
    {
        switch ($dialect) {
            case Writer::DIALECT_MYSQL:
                return new MySQLQueryBuilder();
            case Writer::DIALECT_PGSQL:
                return new PostgreSQLQueryBuilder();
        }

        return new GenericQueryBuilder();
    }
}
