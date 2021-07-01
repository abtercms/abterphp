<?php

declare(strict_types=1);

namespace AbterPhp\PropellerAdmin\Decorator;

use AbterPhp\Framework\Decorator\Decorator;
use AbterPhp\Framework\Decorator\Rule;
use AbterPhp\Framework\Html\Component\Button;
use AbterPhp\Framework\Html\Tag;

class General extends Decorator
{
    public const BUTTON_CLASS = 'btn';

    /** @var array<string,string[]> */
    protected array $buttonIntentMap = [
        Button::INTENT_PRIMARY   => ['btn-primary'],
        Button::INTENT_SECONDARY => ['btn-secondary'],
        Button::INTENT_DANGER    => ['btn-danger'],
        Button::INTENT_SUCCESS   => ['btn-success'],
        Button::INTENT_INFO      => ['btn-info'],
        Button::INTENT_WARNING   => ['btn-warning'],
        Button::INTENT_LINK      => ['btn-link'],
        Button::INTENT_DEFAULT   => ['btn-default'],

        Button::INTENT_SMALL => ['btn-sm'],
        Button::INTENT_LARGE => ['btn-lg'],

        Button::INTENT_FAB     => ['pmd-btn-fab'],
        Button::INTENT_FLAT    => ['pmd-btn-flat'],
        Button::INTENT_RAISED  => ['pmd-btn-raised'],
        Button::INTENT_OUTLINE => ['pmd-btn-outline'],
        Button::INTENT_RIPPLE  => ['pmd-ripple-effect '],
    ];

    /** @var array */
    protected array $intentToClassesMap = [
        Tag::INTENT_HIDDEN => ['hidden'],
        Button::INTENT_ICON   => ['material-icons'],
        Button::INTENT_SMALL  => ['pmd-sm'],
        Button::INTENT_LARGE  => ['pmd-lg'],
    ];

    protected bool $initialized = false;

    /**
     * @return Decorator
     */
    public function init(): Decorator
    {
        if ($this->initialized) {
            return $this;
        }

        $this->initialized = true;

        // Add the appropriate class to components
        $this->rules[] = new Rule([], null, [], $this->intentToClassesMap);

        // Add the appropriate class to buttons
        $this->rules[] = new Rule([], Button::class, [static::BUTTON_CLASS], $this->buttonIntentMap);

        return $this;
    }
}
