<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Tests\TestDouble\Orm;

use Opulence\Orm\Ids\Generators\IIdGenerator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MockIdGeneratorFactory
{
    /**
     * @suppress PhanTypeMismatchDeclaredReturn
     *
     * @param TestCase $testCase
     * @param string   ...$ids
     *
     * @return IIdGenerator|MockObject
     */
    public static function create(TestCase $testCase, string ...$ids): IIdGenerator
    {
        /** @var IIdGenerator|MockObject $idGeneratorMock */
        $idGeneratorMock = $testCase->getMockBuilder(IIdGenerator::class)->getMock();

        $idGeneratorMock
            ->expects($testCase->exactly(count($ids)))
            ->method('generate')
            ->willReturnOnConsecutiveCalls(...$ids);

        return $idGeneratorMock;
    }
}
