<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html\Component;

class ComponentTest extends \PHPUnit\Framework\TestCase
{
    const DEFAULT_TEMPLATE = '<div foo="foo baz" bar="bar baz">Test</div>';

    const LABEL = 'Test';
    const TAG   = 'div';

    const ATTRIBUTE_FOO = 'foo';
    const ATTRIBUTE_BAR = 'bar';
    const ATTRIBUTE_BAZ = 'baz';

    const VALUE_FOO     = 'foo';
    const VALUE_BAR     = 'bar';
    const VALUE_BAZ     = 'baz';
    const VALUE_FOO_BAZ = 'foo baz';
    const VALUE_BAR_BAZ = 'bar baz';

    /** @var Component */
    protected $sut;

    public function setUp()
    {
        $this->sut = new Component(static::LABEL, static::TAG, $this->getDefaultAttributes());
    }

    public function testToStringReturnsExpectedHtml()
    {
        $this->assertSame(static::DEFAULT_TEMPLATE, (string)$this->sut);
    }

    public function testAppendToAttributeLeavesOldAttributesIntact()
    {
        $this->sut->appendToAttribute(static::ATTRIBUTE_BAR, static::VALUE_FOO);

        $attributes = $this->sut->getAttributes();

        $this->assertContains(static::VALUE_BAR_BAZ, $attributes[static::ATTRIBUTE_BAR]);
    }

    public function testAppendToAttributeOnlyModifiesOneAttributeOnly()
    {
        $this->sut->appendToAttribute(static::ATTRIBUTE_BAR, static::VALUE_FOO);

        $attributes = $this->sut->getAttributes();

        $this->assertSame(static::VALUE_FOO_BAZ, $attributes[static::ATTRIBUTE_FOO]);
    }

    public function testAppendToAttributeCanAppendToExistingAttribute()
    {
        $this->sut->appendToAttribute(static::ATTRIBUTE_BAR, static::VALUE_FOO);

        $attributes = $this->sut->getAttributes();

        $this->assertContains(static::VALUE_BAZ, $attributes[static::ATTRIBUTE_BAR]);
    }

    public function testAppendToAttributeCanAddNewAttribute()
    {
        $this->sut->appendToAttribute(static::ATTRIBUTE_BAZ, static::VALUE_BAZ);

        $attributes = $this->sut->getAttributes();

        $this->assertSame(static::VALUE_BAZ, $attributes[static::ATTRIBUTE_BAZ]);
    }

    /**
     * @return array
     */
    protected function getDefaultAttributes(): array
    {
        return [
            static::ATTRIBUTE_FOO => static::VALUE_FOO_BAZ,
            static::ATTRIBUTE_BAR => static::VALUE_BAR_BAZ,
        ];
    }
}
