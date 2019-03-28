<?php

declare(strict_types=1);

namespace AbterPhp\PropellerAdmin\Decorator;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Html\Component;
use PHPUnit\Framework\TestCase;

class GeneralTest extends TestCase
{
    /** @var General */
    protected $sut;

    public function setUp()
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
        $this->sut->decorate([new Component()]);

        $this->assertTrue(true, 'No error was found.');
    }

    public function testDecorateHiddenComponents()
    {
        $component1 = new Component('a', [Component::INTENT_HIDDEN]);
        $component2 = new Component('b', []);
        $component3 = new Component('c', [Component::INTENT_HIDDEN]);

        $this->sut->decorate([$component1, $component2, $component3]);

        $this->assertTrue($component1->hasAttribute(Html5::ATTR_CLASS));
        $this->assertContains(Component::CLASS_HIDDEN, $component1->getAttribute(Html5::ATTR_CLASS));
        $this->assertFalse($component2->hasAttribute(Html5::ATTR_CLASS));
        $this->assertTrue($component3->hasAttribute(Html5::ATTR_CLASS));
        $this->assertContains(Component::CLASS_HIDDEN, $component3->getAttribute(Html5::ATTR_CLASS));
    }

    public function testDecorateButtons()
    {
        $component1 = new Component\Button('a');
        $component2 = new Component('b');
        $component3 = new Component\Button('c');

        $this->sut->decorate([$component1, $component2, $component3]);

        $this->assertTrue($component1->hasAttribute(Html5::ATTR_CLASS));
        $this->assertContains(General::BUTTON_CLASS, $component1->getAttribute(Html5::ATTR_CLASS));
        $this->assertFalse($component2->hasAttribute(Html5::ATTR_CLASS));
        $this->assertTrue($component3->hasAttribute(Html5::ATTR_CLASS));
        $this->assertContains(General::BUTTON_CLASS, $component3->getAttribute(Html5::ATTR_CLASS));
    }
}
