<?php

declare(strict_types=1);

namespace AbterPhp\PropellerAdmin\Navigation;

use AbterPhp\Framework\Html\Component\Button;
use AbterPhp\Framework\Html\Component\IComponent;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Navigation\Item;

class Header extends Item
{
    const DEFAULT_TAG = self::TAG_DIV;

    const HAMBURGER_BTN_ICON  = '<i class="material-icons">menu</i>';
    const HAMBURGER_BTN_CLASS = 'btn btn-sm pmd-btn-fab pmd-btn-flat pmd-ripple-effect pull-left margin-r8 pmd-sidebar-toggle'; // nolint
    const HAMBURGER_BTN_HREF  = 'javascript:void(0);';

    const BRAND_BTN_CLASS = 'navbar-brand';

    /** @var array */
    protected $attributes = [
        self::ATTRIBUTE_CLASS => 'navbar-header',
    ];

    /** @var IComponent */
    protected $homeBtn;

    /** @var IComponent */
    protected $hamburgerBtn;

    /**
     * Header constructor.
     *
     * @param Button           $brandBtn
     * @param IComponent|null  $hamburgerBtn
     * @param array            $attributes
     * @param ITranslator|null $translator
     * @param string|null      $tag
     */
    public function __construct(
        Button $brandBtn,
        IComponent $hamburgerBtn = null,
        array $attributes = [],
        ?ITranslator $translator = null,
        ?string $tag = null
    ) {
        parent::__construct('', $attributes, $translator, $tag);

        $this->homeBtn      = $brandBtn->appendToAttribute(Button::ATTRIBUTE_CLASS, static::BRAND_BTN_CLASS);
        $this->hamburgerBtn = $hamburgerBtn ?: $this->createDefaultHamburgerBtn();
    }

    /**
     * @return Button
     */
    protected function createDefaultHamburgerBtn(): Button
    {
        return new Button(
            static::HAMBURGER_BTN_ICON,
            [
                Button::ATTRIBUTE_CLASS => static::HAMBURGER_BTN_CLASS,
                Button::ATTRIBUTE_HREF  => static::HAMBURGER_BTN_HREF,
            ],
            $this->translator,
            Button::TAG_A
        );
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $this->content = (string)$this->hamburgerBtn . (string)$this->homeBtn;

        return parent::__toString();
    }
}
