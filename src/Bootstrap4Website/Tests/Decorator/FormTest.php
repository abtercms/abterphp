<?php

declare(strict_types=1);

namespace AbterPhp\Bootstrap4Website\Tests\Decorator;

use AbterPhp\Bootstrap4Website\Decorator\Form;
use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Form\Container\CheckboxGroup;
use AbterPhp\Framework\Form\Container\FormGroup;
use AbterPhp\Framework\Form\Element\IElement;
use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Element\Select;
use AbterPhp\Framework\Form\Element\Textarea;
use AbterPhp\Framework\Form\Extra\DefaultButtons;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\Html\Component\Button;
use AbterPhp\Framework\Html\INode;
use PHPUnit\Framework\TestCase;

class FormTest extends TestCase
{
    /** @var Form - System Under Test */
    protected Form $sut;

    public function setUp(): void
    {
        $this->sut = new Form();

        $this->sut->init();
    }

    /**
     * @return array
     */
    public function decorateProvider(): array
    {
        return [
            'label'           => [new Label(''), Form::DEFAULT_LABEL_CLASS],
            'textarea'        => [new Textarea('', ''), Form::DEFAULT_INPUT_CLASS],
            'select'          => [new Select('', ''), Form::DEFAULT_INPUT_CLASS],
            'form-group'      => [
                new FormGroup(
                    $this->createMock(IElement::class),
                    $this->createMock(Label::class)
                ),
                Form::DEFAULT_FORM_GROUP_CLASS,
            ],
            'default-buttons' => [new DefaultButtons(), Form::DEFAULT_BUTTONS_CLASS],
            'buttons'         => [new Button(null, [Button::INTENT_FORM]), Form::DEFAULT_BUTTON_CLASS],
        ];
    }

    /**
     * @dataProvider decorateProvider
     *
     * @param INode  $node
     * @param string $contains
     */
    public function testDecorate(INode $node, string $contains)
    {
        $this->sut->decorate([$node]);

        $this->assertStringContainsString($contains, (string)$node);
    }

    public function testDecorateCheckboxGroup()
    {
        $inputMock = $this->createMock(Input::class);

        $labelMock = $this->createMock(Label::class);

        $checkboxGroup = new CheckboxGroup($inputMock, $labelMock);

        $this->sut->decorate([$checkboxGroup]);

        $actualClass = $checkboxGroup->getAttribute(Html5::ATTR_CLASS)->getValue();

        $this->assertStringContainsString(Form::CHECKBOX_GROUP_CLASS, $actualClass);
    }
}
