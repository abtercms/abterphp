<?php

declare(strict_types=1);

namespace AbterPhp\PropellerAdmin\Decorator;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Decorator\Decorator;
use AbterPhp\Framework\Decorator\Rule;
use AbterPhp\Framework\Form\Container\FormGroup;
use AbterPhp\Framework\Form\Container\CheckboxGroup;
use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Element\Select;
use AbterPhp\Framework\Form\Element\Textarea;
use AbterPhp\Framework\Form\Extra\DefaultButtons;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\Html\Component\Button;

class Form extends Decorator
{
    const DEFAULT_FORM_GROUP_CLASS = 'form-group';
    const DEFAULT_LABEL_CLASS      = 'control-label';
    const DEFAULT_INPUT_CLASS      = 'form-control';
    const DEFAULT_BUTTONS_CLASS    = 'form-group pmd-textfield pmd-textfield-floating-label';
    const DEFAULT_BUTTON_CLASS     = 'pmd-checkbox-ripple-effect';

    const CHECKBOX_GROUP_CLASS = 'checkbox pmd-default-theme';
    const CHECKBOX_LABEL_CLASS = 'pmd-checkbox pmd-checkbox-ripple-effect';

    /**
     * @return Decorator
     */
    public function init(): Decorator
    {
        // Add the appropriate class to form labels
        $this->rules[] = new Rule([], Label::class, [static::DEFAULT_LABEL_CLASS]);

        // Add the appropriate class to form labels
        $this->rules[] = new Rule([], Input::class, [static::DEFAULT_INPUT_CLASS]);
        $this->rules[] = new Rule([], Textarea::class, [static::DEFAULT_INPUT_CLASS]);
        $this->rules[] = new Rule([], Select::class, [static::DEFAULT_INPUT_CLASS]);
        $this->rules[] = new Rule([], FormGroup::class, [static::DEFAULT_FORM_GROUP_CLASS]);
        $this->rules[] = new Rule([], DefaultButtons::class, [static::DEFAULT_BUTTONS_CLASS]);
        $this->rules[] = new Rule([Button::INTENT_FORM], Button::class, [static::DEFAULT_BUTTON_CLASS]);
        $this->rules[] = new Rule([], CheckboxGroup::class, [], [], [$this, 'decorateCheckboxGroup']);

        return $this;
    }

    /**
     * @param CheckboxGroup $checkboxGroup
     */
    public function decorateCheckboxGroup(CheckboxGroup $checkboxGroup)
    {
        $checkboxGroup->setAttribute(Html5::ATTR_CLASS, static::CHECKBOX_GROUP_CLASS);

        $checkboxGroup->getLabel()->setAttribute(Html5::ATTR_CLASS, static::CHECKBOX_LABEL_CLASS);
        $checkboxGroup->getInput()->unsetAttribute(Html5::ATTR_CLASS);
    }
}
