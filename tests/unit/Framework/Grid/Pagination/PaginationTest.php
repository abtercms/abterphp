<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Pagination;

use AbterPhp\Framework\I18n\Translator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PaginationTest extends TestCase
{
    /**
     * @return array
     */
    public function getTestToStringDataProvider()
    {
        return [
            [1, 10, 8, 5, [1]],
//            [1, 10, 12, 5, [1, 2]],
//            [2, 10, 38, 5, [1, 2, 3, 4]],
//            [3, 10, 38, 3, [3, 4]],
//            [14, 20, 942, 5, [6, 7, 8, 9, 10]],
//            [200, 20, 5000, 9, [97, 98, 99, 100, 101, 102, 103, 104, 105]],
        ];
    }

    /**
     * @dataProvider getTestToStringDataProvider
     *
     * @param int   $page
     * @param int   $pageSize
     * @param int   $totalCount
     * @param int   $numberCount
     * @param array $expectedResult
     */
    public function testToString($page, $pageSize, $totalCount, $numberCount, $expectedResult)
    {
        /** @var Translator|MockObject $translatorMock */
        $translatorMock = $this->getMockBuilder(Translator::class)
            ->disableOriginalConstructor()
            ->setMethods(['translate'])
            ->getMock()
        ;

        $translatorMock->expects($this->any())->method('translate')->willReturnArgument(0);

        $params = ['page' => (string)$page];

        $sut = new Pagination($params, '', $numberCount, $pageSize, [$pageSize], [], $translatorMock);

        $sut->setTotalCount($totalCount);

        $actualResult = $sut->__toString();

        foreach ($expectedResult as $number) {
            $this->assertContains("$number", $actualResult);
        }
    }
}
