<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Tests\Bootstrappers\Databases;

use AbterPhp\Framework\Bootstrappers\Database\MigrationsBootstrapper;
use AbterPhp\Framework\Database\PDO\Writer;
use Opulence\Databases\Migrations\IMigrator;
use Opulence\Ioc\Container;
use PHPUnit\Framework\TestCase;
use QB\Generic\QueryBuilder\IQueryBuilder;
use QB\MySQL\QueryBuilder\QueryBuilder;

class MigrationsBootstrapperTest extends TestCase
{
    /** @var MigrationsBootstrapper - System Under Test */
    protected MigrationsBootstrapper $sut;

    public function setUp(): void
    {
        $this->sut = new MigrationsBootstrapper();
    }

    public function testRegisterBindings(): void
    {
        $writerStub = $this->createStub(Writer::class);
        $queryBuilderStub = $this->createStub(QueryBuilder::class);

        $this->sut->setMigrationPaths([]);

        $container = new Container();
        $container->bindInstance(Writer::class, $writerStub);
        $container->bindInstance(IQueryBuilder::class, $queryBuilderStub);

        $this->sut->registerBindings($container);

        $actual = $container->resolve(IMigrator::class);
        $this->assertInstanceOf(IMigrator::class, $actual);
    }
}
