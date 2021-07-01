<?php

declare(strict_types=1);

namespace AbterPhp\PropellerAdmin\Tests\Decorator;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Html\Component\Button;
use AbterPhp\Framework\Html\Tag;
use AbterPhp\PropellerAdmin\Decorator\General;
use PHPUnit\Framework\TestCase;

class GeneralTest extends TestCase
{
    /** @var General - System Under Test */
    protected $sut;

    public function setUp(): void
    {
        $this->sut = (new General())->init();

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

    public function testDecorateHiddenComponents()
    {
        $component1 = new Tag('a', [Tag::INTENT_HIDDEN]);
        $component2 = new Tag('b', []);
        $component3 = new Tag('c', [Tag::INTENT_HIDDEN]);

        $this->sut->decorate([$component1, $component2, $component3]);

        $this->assertTrue($component1->hasAttribute(Html5::ATTR_CLASS));
        $this->assertStringContainsString('hidden', $component1->getAttribute(Html5::ATTR_CLASS)->getValue());
        $this->assertFalse($component2->hasAttribute(Html5::ATTR_CLASS));
        $this->assertTrue($component3->hasAttribute(Html5::ATTR_CLASS));
        $this->assertStringContainsString('hidden', $component3->getAttribute(Html5::ATTR_CLASS)->getValue());
    }

    public function testDecorateButtons()
    {
        $component1 = new Button('a');
        $component2 = new Tag('b');
        $component3 = new Button('c');

        $this->sut->decorate([$component1, $component2, $component3]);

        $this->assertTrue($component1->hasAttribute(Html5::ATTR_CLASS));
        $this->assertStringContainsString(
            General::BUTTON_CLASS,
            $component1->getAttribute(Html5::ATTR_CLASS)->getValue()
        );
        $this->assertFalse($component2->hasAttribute(Html5::ATTR_CLASS));
        $this->assertTrue($component3->hasAttribute(Html5::ATTR_CLASS));
        $this->assertStringContainsString(
            General::BUTTON_CLASS,
            $component3->getAttribute(Html5::ATTR_CLASS)->getValue()
        );
    }

    public function testDecorateButtonsWithDoubleInit()
    {
        $this->sut->init();

        $this->testDecorateButtons();
    }
}
