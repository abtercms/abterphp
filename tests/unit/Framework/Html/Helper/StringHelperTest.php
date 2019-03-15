<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html\Helper;

class StringHelperTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return array
     */
    public function wrapInTagDataProvider()
    {
        return [
            'empty'           => ['', null, [], '', ''],
            'simple'          => ['A', null, [], '', 'A'],
            'html'            => ['A', 'B', [], '', '<B>A</B>'],
            'with-attributes' => ['A', 'B', ['foo' => 'foo', 'bar' => 'baz'], '', "<B foo=\"foo\" bar=\"baz\">A</B>"],
            'whitespace'      => [
                'A',
                'B',
                ['foo' => 'foo', 'bar' => 'baz'],
                '  ',
                "  <B foo=\"foo\" bar=\"baz\">\nA\n  </B>",
            ],
        ];
    }

    /**
     * @dataProvider wrapInTagDataProvider
     *
     * @param string      $content
     * @param string|null $tag
     * @param array       $attributes
     * @param string      $whitespace
     * @param string      $expectedResult
     */
    public function testWrapInTag($content, $tag, $attributes, $whitespace, $expectedResult)
    {
        $actualResult = StringHelper::wrapInTag($content, $tag, $attributes, $whitespace);

        $this->assertSame($expectedResult, $actualResult);
    }
}
