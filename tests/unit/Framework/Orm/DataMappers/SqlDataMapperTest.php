<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Orm\DataMapper;

use Opulence\Databases\Adapters\Pdo\Connection;
use Opulence\Databases\Adapters\Pdo\Statement;
use Opulence\Databases\IConnection;
use Opulence\Databases\IStatement;
use PHPUnit\Framework\MockObject\Matcher\AnyInvokedCount;
use PHPUnit\Framework\MockObject\Matcher\InvokedAtIndex;
use PHPUnit\Framework\MockObject\Matcher\InvokedCount;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

abstract class SqlDataMapperTest extends \PHPUnit\Framework\TestCase
{
    const EXPECTATION_ONCE  = -1;
    const EXPECTATION_ANY   = -2;
    const EXPECTATION_NEVER = -4;

    /** @var IConnection|MockObject */
    protected $connection = null;

    /**
     * @return IConnection|MockObject
     */
    public function getConnectionMock()
    {
        $this->connection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->setMethods(['prepare', 'read'])
            ->getMock()
        ;

        return $this->connection;
    }

    /**
     * @param string $sql
     * @param mixed  $returnValue
     * @param int    $at
     */
    protected function prepare(string $sql, $returnValue, int $at = self::EXPECTATION_ONCE)
    {
        $this->connection
            ->expects($this->getExpectation($at))
            ->method('prepare')
            ->with($sql)
            ->willReturn($returnValue)
        ;
    }

    /**
     * @param string|int $nextId
     * @param int        $at
     */
    protected function lastInsertId($nextId, int $at = self::EXPECTATION_ONCE)
    {
        $this->connection
            ->expects($this->getExpectation($at))
            ->method('lastInsertId')
            ->willReturn($nextId)
        ;
    }

    /**
     * @param array $expectedData
     * @param array $collection
     */
    protected function assertCollection(array $expectedData, $collection)
    {
        $this->assertNotNull($collection);
        $this->assertInternalType('array', $collection);
        $this->assertCount(count($expectedData), $collection);

        foreach ($collection as $key => $entity) {
            $this->assertEntity($expectedData[$key], $entity);
        }
    }

    /**
     * @param array  $expectedData
     * @param object $entity
     */
    abstract protected function assertEntity(array $expectedData, $entity);

    /**
     * @param array $values
     * @param array $rows
     * @param int   $atBindValues
     * @param int   $atExecute
     * @param int   $atRowCount
     * @param int   $atFetchAll
     *
     * @return IStatement|MockObject
     */
    protected function createReadStatement(
        array $values,
        array $rows,
        int $atBindValues = self::EXPECTATION_ONCE,
        int $atExecute = self::EXPECTATION_ONCE,
        int $atRowCount = self::EXPECTATION_ANY,
        int $atFetchAll = self::EXPECTATION_ONCE
    ) {
        $statement = $this->createStatement();
        $statement->expects($this->getExpectation($atBindValues))->method('bindValues')->with($values);
        $statement->expects($this->getExpectation($atExecute))->method('execute');
        $statement->expects($this->getExpectation($atRowCount))->method('rowCount')->willReturn(count($rows));
        $statement->expects($this->getExpectation($atFetchAll))->method('fetchAll')->willReturn($rows);

        return $statement;
    }

    /**
     * @param array $values
     * @param int   $atBindValues
     * @param int   $atExecute
     *
     * @return IStatement|MockObject
     */
    protected function createWriteStatement(
        array $values,
        int $atBindValues = self::EXPECTATION_ONCE,
        int $atExecute = self::EXPECTATION_ONCE
    ) {
        $statement = $this->createStatement();
        $statement->expects($this->getExpectation($atBindValues))->method('bindValues')->with($values);
        $statement->expects($this->getExpectation($atExecute))->method('execute');

        return $statement;
    }

    /**
     * @return IStatement|MockObject
     */
    protected function createStatement()
    {
        /** @var IStatement|MockObject $mock */
        $statement = $this->getMockBuilder(Statement::class)
            ->disableOriginalConstructor()
            ->setMethods(['bindValues', 'execute', 'rowCount', 'fetchAll'])
            ->getMock()
        ;

        return $statement;
    }

    /**
     * @param int $at
     *
     * @return AnyInvokedCount|InvokedAtIndex|InvokedCount
     */
    protected function getExpectation(int $at)
    {
        switch ($at) {
            case static::EXPECTATION_NEVER:
                return $this->never();
            case static::EXPECTATION_ONCE:
                return $this->once();
            case static::EXPECTATION_ANY:
                return $this->any();
        }

        return $this->at($at);
    }
}
