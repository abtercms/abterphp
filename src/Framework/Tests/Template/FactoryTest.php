<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Tests\Template;

use AbterPhp\Framework\Template\Factory;
use AbterPhp\Framework\Template\Template;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /** @var Factory - System Under Test */
    protected Factory $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new Factory();
    }

    public function testCreate(): void
    {
        $rawContent = 'foo';

        $actualResult = $this->sut->create($rawContent);

        $this->assertInstanceOf(Template::class, $actualResult);
        $this->assertSame($rawContent, $actualResult->render([]));
    }
}
