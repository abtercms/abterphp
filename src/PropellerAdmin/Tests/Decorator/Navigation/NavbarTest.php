<?php

declare(strict_types=1);

namespace AbterPhp\PropellerAdmin\Tests\Decorator\Navigation;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Html\Tag;
use AbterPhp\Framework\Navigation\Navigation;
use AbterPhp\PropellerAdmin\Decorator\Navigation\Navbar;
use Opulence\Routing\Urls\UrlGenerator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class NavbarTest extends TestCase
{
    /** @var Navbar - System Under Test */
    protected $sut;

    /** @var UrlGenerator|MockObject */
    protected $urlGeneratorMock;

    public function setUp(): void
    {
        $this->urlGeneratorMock = $this->getMockBuilder(UrlGenerator::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createFromName'])
            ->getMock();

        $this->sut = (new Navbar($this->urlGeneratorMock))->init();

        parent::setUp();
    }

    public function testDecorateWithEmptyComponents()
    {
        $this->sut->decorate([]);

        $this->assertTrue(true, 'No error was found.');
    }

    public function testDecorateNonMatchingComponents()
    {
        $this->sut->decorate([new Tag()]);

        $this->assertTrue(true, 'No error was found.');
    }

    public function testDecorateWithDoubleInit()
    {
        $this->sut->init();
        $this->sut->decorate([new Tag()]);

        $this->testDecorateNonMatchingComponents();
    }

    public function testDecorateNavbar()
    {
        $navbar = new Navigation([Navigation::INTENT_NAVBAR]);

        $this->sut->decorate([$navbar]);

        $this->assertStringContainsString(Navbar::NAVBAR_CLASS, $navbar->getAttribute(Html5::ATTR_CLASS)->getValue());
        $this->assertCount(1, $navbar->getNodes());
    }
}
