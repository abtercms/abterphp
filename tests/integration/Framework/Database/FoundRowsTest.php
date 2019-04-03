<?php


namespace Integration\AbterPhp\Framework\Databases\Queries;

use AbterPhp\Framework\Databases\Queries\FoundRows;
use Integration\Framework\Console\IntegrationTestCase;
use Opulence\Databases\ConnectionPools\ConnectionPool;

class FoundRowsTest extends IntegrationTestCase
{
    public function testGet()
    {
        /** @var ConnectionPool $connectionPool */
        $connectionPool = $this->container->resolve(ConnectionPool::class);

        $sut = new FoundRows($connectionPool);

        $sql = 'SELECT SQL_CALC_FOUND_ROWS * FROM user_groups LIMIT 1';

        $connection = $connectionPool->getReadConnection();
        $statement  = $connection->prepare($sql);

        if (!$statement->execute()) {
            $this->fail('execute failed');
        }

        $foundRows = $sut->get();

        $this->assertGreaterThan(1, $foundRows);
    }
}
