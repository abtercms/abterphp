<?php

declare(strict_types=1);

namespace AbterPhp\PropellerAdmin\Decorator;

use AbterPhp\Framework\Decorator\Decorator;
use AbterPhp\Framework\Decorator\Rule;
use AbterPhp\Framework\Html\Component;
use AbterPhp\Framework\Html\Component\Button;

class General extends Decorator
{
    const BUTTON_CLASS = 'btn';

    /** @var array */
    protected $buttonIntentMap = [
        Button::INTENT_PRIMARY   => ['btn-primary'],
        Button::INTENT_SECONDARY => ['btn-secondary'],
        Button::INTENT_DANGER    => ['btn-danger'],
        Button::INTENT_SUCCESS   => ['btn-success'],
        Button::INTENT_INFO      => ['btn-info'],
        Button::INTENT_WARNING   => ['btn-warning'],
        Button::INTENT_LINK      => ['btn-link'],
        Button::INTENT_SMALL     => ['btn-sm'],
        Button::INTENT_LARGE     => ['btn-lg'],
        Button::INTENT_HIDDEN    => ['btn-secondary'],
    ];

    protected $initialized = false;

    /**
     * @return Decorator
     */
    public function init(): Decorator
    {
        if ($this->initialized) {
            return $this;
        }

        $this->initialized = true;

        // Add the class "hidden" to all hidden elements
        $this->rules[] = new Rule([Component::INTENT_HIDDEN], null, [Component::CLASS_HIDDEN]);

        // Add the appropriate class to buttons
        $this->rules[] = new Rule([], Button::class, [static::BUTTON_CLASS], $this->buttonIntentMap);

        return $this;
    }
}
