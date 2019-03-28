<?php

declare(strict_types=1);

namespace AbterPhp\PropellerAdmin\Decorator;

use AbterPhp\Framework\Decorator\Decorator;
use AbterPhp\Framework\Decorator\Rule;
use AbterPhp\Framework\Form\Container\FormGroup;
use AbterPhp\Framework\Form\Container\ToggleGroup;
use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Element\Select;
use AbterPhp\Framework\Form\Element\Textarea;
use AbterPhp\Framework\Form\Extra\DefaultButtons;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\Html\Component\Button;

class Form extends Decorator
{
    const FORM_GROUP_CLASS = 'form-group';

    const LABEL_CLASS = 'control-label';

    const INPUT_CLASS = 'form-control';

    const DEFAULT_BUTTONS_CLASS = 'form-group pmd-textfield pmd-textfield-floating-label';

    const BUTTON_CLASS = 'pmd-checkbox-ripple-effect';

    const TOGGLE_GROUP_CLASS = 'pmd-switch';

    const TOGGLE_LABEL_CLASS = 'pmd-checkbox pmd-checkbox-ripple-effect';

    const TOGGLE_SPAN_CLASS = 'pmd-switch-label';

    /**
     * @return Decorator
     */
    public function init(): Decorator
    {
        // Add the appropriate class to form labels
        $this->rules[] = new Rule([], Label::class, [static::LABEL_CLASS]);

        // Add the appropriate class to form labels
        $this->rules[] = new Rule([], Input::class, [static::INPUT_CLASS]);
        $this->rules[] = new Rule([], Textarea::class, [static::INPUT_CLASS]);
        $this->rules[] = new Rule([], Select::class, [static::INPUT_CLASS]);
        $this->rules[] = new Rule([], FormGroup::class, [static::FORM_GROUP_CLASS]);
        $this->rules[] = new Rule([], DefaultButtons::class, [static::DEFAULT_BUTTONS_CLASS]);
        $this->rules[] = new Rule([Button::INTENT_FORM], Button::class, [static::BUTTON_CLASS]);
        $this->rules[] = new Rule([], ToggleGroup::class, [static::TOGGLE_GROUP_CLASS]);

        return $this;
    }
}
