<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Databases\Migrations;

use AbterPhp\Framework\Databases\QueryFileLoader;
use PHPUnit\Framework\TestCase;

class QueryFileLoaderTest extends TestCase
{
    protected $migrationsPath = '/migrations';

    public function testDown()
    {
        $driver = 'foo';

        $sut = $this->createSut($this->migrationsPath, $driver);

        $this->markTestIncomplete();
    }

    public function testUp()
    {
        $driver = 'foo';

        $sut = $this->createSut($this->migrationsPath, $driver);

        $this->markTestIncomplete();
    }

    /**
     * @param string $migrationsPath
     * @param string $driver
     */
    protected function createSut(string $migrationsPath, string $driver)
    {
        $this->sut = new QueryFileLoader($migrationsPath, $driver);
    }
}
