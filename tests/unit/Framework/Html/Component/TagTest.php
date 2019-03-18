<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html\Component;

use AbterPhp\Framework\I18n\MockTranslatorFactory;

class TagTest extends \PHPUnit\Framework\TestCase
{
    const LABEL = 'Test';
    const TAG   = 'yo';

    const ATTRIBUTE_FOO = 'foo';
    const ATTRIBUTE_BAR = 'bar';
    const ATTRIBUTE_BAZ = 'baz';

    const VALUE_FOO     = 'foo';
    const VALUE_BAR     = 'bar';
    const VALUE_BAZ     = 'baz';
    const VALUE_FOO_BAZ = 'foo baz';
    const VALUE_BAR_BAZ = 'bar baz';

    public function toStringProvider(): array
    {
        return [
            'no-attributes'     => [
                [],
                '<yo>Test</yo>',
            ],
            'string-attributes' => [
                [
                    static::ATTRIBUTE_FOO => static::VALUE_FOO_BAZ,
                    static::ATTRIBUTE_BAR => static::VALUE_BAR_BAZ,
                ],
                '<yo foo="foo baz" bar="bar baz">Test</yo>',
            ],
            'array-attributes'  => [
                [
                    static::ATTRIBUTE_FOO => [static::VALUE_FOO, static::VALUE_BAZ],
                    static::ATTRIBUTE_BAR => [static::VALUE_BAR, static::VALUE_BAZ],
                ],
                '<yo foo="foo baz" bar="bar baz">Test</yo>',
            ],
            'mixed-attributes'  => [
                [
                    static::ATTRIBUTE_FOO => [static::VALUE_FOO, static::VALUE_BAZ],
                    static::ATTRIBUTE_BAR => static::VALUE_BAR_BAZ,
                ],
                '<yo foo="foo baz" bar="bar baz">Test</yo>',
            ],
        ];
    }

    /**
     * @dataProvider toStringProvider
     *
     * @param array  $attributes
     * @param string $expectedResult
     */
    public function testToStringReturnsExpectedHtml(array $attributes, string $expectedResult)
    {
        $sut = new Tag(static::LABEL, $attributes, null, static::TAG);

        $this->assertSame($expectedResult, (string)$sut);
    }

    public function testAppendToAttributeLeavesOldAttributesIntact()
    {
        $attributes = [
            static::ATTRIBUTE_FOO => static::VALUE_FOO_BAZ,
            static::ATTRIBUTE_BAR => static::VALUE_BAR_BAZ,
        ];

        $sut = new Tag(static::LABEL, $attributes, null, static::TAG);

        $sut->appendToAttribute(static::ATTRIBUTE_BAR, static::VALUE_FOO);

        $attributes = $sut->getAttributes();

        $this->assertContains(static::VALUE_BAR_BAZ, $attributes[static::ATTRIBUTE_BAR]);
    }

    public function testAppendToAttributeOnlyModifiesOneAttributeOnly()
    {
        $attributes = [
            static::ATTRIBUTE_FOO => static::VALUE_FOO_BAZ,
            static::ATTRIBUTE_BAR => static::VALUE_BAR_BAZ,
        ];

        $sut = new Tag(static::LABEL, $attributes, null, static::TAG);

        $sut->appendToAttribute(static::ATTRIBUTE_BAR, static::VALUE_FOO);

        $attributes = $sut->getAttributes();

        $this->assertSame(static::VALUE_FOO_BAZ, $attributes[static::ATTRIBUTE_FOO]);
    }

    public function testAppendToAttributeCanAppendToExistingAttribute()
    {
        $attributes = [
            static::ATTRIBUTE_FOO => static::VALUE_FOO_BAZ,
            static::ATTRIBUTE_BAR => static::VALUE_BAR_BAZ,
        ];

        $sut = new Tag(static::LABEL, $attributes, null, static::TAG);

        $sut->appendToAttribute(static::ATTRIBUTE_BAR, static::VALUE_FOO);

        $attributes = $sut->getAttributes();

        $this->assertContains(static::VALUE_FOO, $attributes[static::ATTRIBUTE_BAR]);
    }

    public function testAppendToAttributeCanAddNewAttribute()
    {
        $attributes = [
            static::ATTRIBUTE_FOO => static::VALUE_FOO_BAZ,
            static::ATTRIBUTE_BAR => static::VALUE_BAR_BAZ,
        ];

        $sut = new Tag(static::LABEL, $attributes, null, static::TAG);

        $sut->appendToAttribute(static::ATTRIBUTE_BAZ, static::VALUE_BAZ);

        $attributes = $sut->getAttributes();

        $this->assertSame(static::VALUE_BAZ, $attributes[static::ATTRIBUTE_BAZ]);
    }

    public function testToStringWitSettersComplex()
    {
        $expectedResult = '<yolo foo="foo foo bar"><div>Testing</div></yolo>';

        $origAttributes = [
            static::ATTRIBUTE_FOO => static::VALUE_FOO_BAZ,
            static::ATTRIBUTE_BAR => static::VALUE_BAR_BAZ,
        ];

        $sut = new Tag(static::LABEL, $origAttributes, null, static::TAG);

        $sut->setAttributes([]);

        $sut->mergeAttributes([static::ATTRIBUTE_FOO => static::VALUE_FOO]);
        $sut->appendToAttribute(static::ATTRIBUTE_FOO, static::VALUE_FOO);
        $sut->mergeAttributes([static::ATTRIBUTE_FOO => static::VALUE_BAR]);
        $sut->setTag('yolo');
        $sut->setContent(new Tag('Testing'));

        $this->assertSame($expectedResult, (string)$sut);
    }

    public function testToStringWithTranslation()
    {
        $translator = MockTranslatorFactory::createSimpleTranslator($this, ['Test' => 'Teszt']);

        $expectedResult = '<yo foo="foo baz" bar="bar baz">Teszt</yo>';

        $origAttributes = [
            static::ATTRIBUTE_FOO => static::VALUE_FOO_BAZ,
            static::ATTRIBUTE_BAR => static::VALUE_BAR_BAZ,
        ];

        $sut = new Tag(static::LABEL, $origAttributes, null, static::TAG);

        $sut->setTranslator($translator);

        $this->assertSame($expectedResult, (string)$sut);
    }
}
