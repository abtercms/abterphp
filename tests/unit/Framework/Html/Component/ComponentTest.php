<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html\Component;

use AbterPhp\Framework\I18n\MockTranslatorFactory;

class ComponentTest extends \PHPUnit\Framework\TestCase
{
    const CONTENT = 'Test';
    const TRANSLATED_CONTENT = 'Teszt';

    public function testToStringReturnsExpectedContent()
    {
        $sut = new Component(static::CONTENT);

        $this->assertSame(static::CONTENT, (string)$sut);
    }

    public function testToStringWitSettersComplex()
    {
        $sut = new Component(static::CONTENT);

        $sut->setAttributes(['foo' => 'bar']);

        $sut->setTag('yolo');
        $sut->setContent(new Component(static::TRANSLATED_CONTENT));

        $this->assertSame(static::TRANSLATED_CONTENT, (string)$sut);
    }

    public function testToStringWithTranslation()
    {
        $translator = MockTranslatorFactory::createSimpleTranslator(
            $this,
            [static::CONTENT => static::TRANSLATED_CONTENT]
        );

        $sut = new Component(static::CONTENT, $translator);

        $this->assertSame(static::TRANSLATED_CONTENT, (string)$sut);
    }
}
