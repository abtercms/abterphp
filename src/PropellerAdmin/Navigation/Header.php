<?php

declare(strict_types=1);

namespace AbterPhp\PropellerAdmin\Navigation;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Html\Component\Button;
use AbterPhp\Framework\Html\Helper\StringHelper;
use AbterPhp\Framework\Html\IComponent;
use AbterPhp\Framework\Navigation\Item;

class Header extends Item
{
    const DEFAULT_TAG = Html5::TAG_DIV;

    const HAMBURGER_BTN_INTENT = 'header-btn';
    const HAMBURGER_BTN_ICON   = '<i class="material-icons">menu</i>';
    const HAMBURGER_BTN_CLASS  = 'btn btn-sm pmd-btn-fab pmd-btn-flat pmd-ripple-effect pull-left margin-r8 pmd-sidebar-toggle'; // nolint
    const HAMBURGER_BTN_HREF   = 'javascript:void(0);';

    const BRAND_BTN_CLASS = 'navbar-brand';

    /** @var array */
    protected $attributes = [
        Html5::ATTR_CLASS => 'navbar-header',
    ];

    /** @var Button */
    protected $brandBtn;

    /** @var IComponent */
    protected $hamburgerBtn;

    /**
     * Header constructor.
     *
     * @param Button          $brandBtn
     * @param IComponent|null $hamburgerBtn
     * @param array           $attributes
     * @param string|null     $tag
     */
    public function __construct(
        Button $brandBtn,
        ?IComponent $hamburgerBtn = null,
        array $attributes = [],
        ?string $tag = null
    ) {
        parent::__construct(null, [], $attributes, $tag);

        $this->brandBtn     = $brandBtn->appendToAttribute(Html5::ATTR_CLASS, static::BRAND_BTN_CLASS);
        $this->hamburgerBtn = $hamburgerBtn ?: $this->createDefaultHamburgerBtn();
    }

    /**
     * @return Button
     */
    protected function createDefaultHamburgerBtn(): Button
    {
        $attribs = [
            Html5::ATTR_CLASS => static::HAMBURGER_BTN_CLASS,
            Html5::ATTR_HREF  => static::HAMBURGER_BTN_HREF,
        ];

        return new Button(static::HAMBURGER_BTN_ICON, [self::HAMBURGER_BTN_INTENT], $attribs, Html5::TAG_A);
    }

    /**
     * @return array
     */
    public function getExtendedNodes(): array
    {
        return array_merge([$this->brandBtn, $this->hamburgerBtn], $this->getNodes());
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $content = (string)$this->hamburgerBtn . (string)$this->brandBtn;

        $content = StringHelper::wrapInTag($content, $this->tag, $this->attributes);

        return $content;
    }
}
