<?php

declare(strict_types=1);

namespace AbterPhp\PropellerAdmin\Tests\Decorator;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Form\Container\CheckboxGroup;
use AbterPhp\Framework\Form\Container\FormGroup;
use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Element\Select;
use AbterPhp\Framework\Form\Element\Textarea;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\Html\Component\Button;
use AbterPhp\Framework\Html\Tag;
use AbterPhp\PropellerAdmin\Decorator\Form;
use PHPUnit\Framework\TestCase;

class FormTest extends TestCase
{
    /** @var Form - System Under Test */
    protected $sut;

    public function setUp(): void
    {
        $this->sut = (new Form())->init();

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

    public function testDecorateLabels()
    {
        $label0 = new Label('a', 'A');
        $label1 = new Label('b', 'B');

        $this->sut->decorate([$label0, $label1]);

        $this->assertStringContainsString(
            Form::DEFAULT_LABEL_CLASS,
            $label0->getAttribute(Html5::ATTR_CLASS)->getValue()
        );
        $this->assertStringContainsString(
            Form::DEFAULT_LABEL_CLASS,
            $label1->getAttribute(Html5::ATTR_CLASS)->getValue()
        );
    }

    public function testDecorateInputs()
    {
        $element0 = new Input('a', 'A');
        $element1 = new Textarea('b', 'B');
        $element2 = new Select('c', 'C');

        $this->sut->decorate([$element0, $element1, $element2]);

        $this->assertStringContainsString(
            Form::DEFAULT_INPUT_CLASS,
            $element0->getAttribute(Html5::ATTR_CLASS)->getValue()
        );
        $this->assertStringContainsString(
            Form::DEFAULT_INPUT_CLASS,
            $element1->getAttribute(Html5::ATTR_CLASS)->getValue()
        );
        $this->assertStringContainsString(
            Form::DEFAULT_INPUT_CLASS,
            $element2->getAttribute(Html5::ATTR_CLASS)->getValue()
        );
    }

    public function testDecorateFormGroups()
    {
        $input      = new Input('a', 'A');
        $label      = new Label('a', 'A');
        $formGroup0 = new FormGroup($input, $label);
        $formGroup1 = new FormGroup($input, $label);

        $this->sut->decorate([$formGroup0, $formGroup1]);

        $this->assertStringContainsString(
            Form::DEFAULT_FORM_GROUP_CLASS,
            $formGroup0->getAttribute(Html5::ATTR_CLASS)->getValue()
        );
        $this->assertStringContainsString(
            Form::DEFAULT_FORM_GROUP_CLASS,
            $formGroup1->getAttribute(Html5::ATTR_CLASS)->getValue()
        );
    }

    public function testDecorateButtons()
    {
        $btn0 = new Button('a', [Button::INTENT_FORM]);
        $btn1 = new Button('b', [Button::INTENT_FORM]);

        $this->sut->decorate([$btn0, $btn1]);

        $this->assertStringContainsString(
            Form::DEFAULT_BUTTON_CLASS,
            $btn0->getAttribute(Html5::ATTR_CLASS)->getValue()
        );
        $this->assertStringContainsString(
            Form::DEFAULT_BUTTON_CLASS,
            $btn1->getAttribute(Html5::ATTR_CLASS)->getValue()
        );
    }

    public function testDecorateCheckboxGroups()
    {
        $input     = new Input('a', 'A');
        $label     = new Label('a', 'A');
        $checkbox0 = new CheckboxGroup($input, $label);
        $checkbox1 = new CheckboxGroup($input, $label);

        $this->sut->decorate([$checkbox0, $checkbox1]);

        $this->assertStringContainsString(
            Form::CHECKBOX_GROUP_CLASS,
            $checkbox0->getAttribute(Html5::ATTR_CLASS)->getValue()
        );
        $this->assertStringContainsString(
            Form::CHECKBOX_GROUP_CLASS,
            $checkbox1->getAttribute(Html5::ATTR_CLASS)->getValue()
        );
    }
}
