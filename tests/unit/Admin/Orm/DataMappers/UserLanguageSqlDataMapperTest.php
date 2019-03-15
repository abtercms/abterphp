<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Orm\DataMapper;

use AbterPhp\Admin\Domain\Entities\UserLanguage;
use AbterPhp\Admin\Orm\DataMappers\UserLanguageSqlDataMapper;
use AbterPhp\Framework\Orm\DataMapper\SqlDataMapperTest;
use Opulence\Databases\Adapters\Pdo\Connection as Connection;

class UserLanguageSqlDataMapperTest extends SqlDataMapperTest
{
    /** @var UserLanguageSqlDataMapper */
    protected $sut;

    public function setUp()
    {
        $this->connection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->setMethods(['prepare', 'read', 'lastInsertId'])
            ->getMock();

        $this->sut = new UserLanguageSqlDataMapper($this->connection, $this->connection);
    }

    public function testAdd()
    {
        $this->markTestIncomplete();
    }

    public function testDelete()
    {
        $this->markTestIncomplete();
    }

    public function testGetAll()
    {
        $this->markTestIncomplete();
    }

    public function testGetById()
    {
        $this->markTestIncomplete();
    }

    public function testUpdate()
    {
        $this->markTestIncomplete();
    }

    /**
     * @param array        $expectedData
     * @param UserLanguage $entity
     */
    protected function assertEntity(array $expectedData, $entity)
    {
        $this->assertInstanceOf(UserLanguage::class, $entity);
    }
}
