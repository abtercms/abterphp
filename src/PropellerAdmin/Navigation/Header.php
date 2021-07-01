<?php

declare(strict_types=1);

namespace AbterPhp\PropellerAdmin\Navigation;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Html\Attribute;
use AbterPhp\Framework\Html\Component\Button;
use AbterPhp\Framework\Html\Helper\Tag as TagHelper;
use AbterPhp\Framework\Html\ITag;
use AbterPhp\Framework\Navigation\Item;

class Header extends Item
{
    protected const DEFAULT_TAG = Html5::TAG_DIV;

    protected const HAMBURGER_BTN_INTENT = 'header-btn';
    protected const HAMBURGER_BTN_ICON   = '<i class="material-icons">menu</i>';
    protected const HAMBURGER_BTN_CLASS  = 'btn btn-sm pmd-btn-fab pmd-btn-flat pmd-ripple-effect pull-left margin-r8 pmd-sidebar-toggle'; // nolint
    protected const HAMBURGER_BTN_HREF   = 'javascript:void(0);';

    protected const BRAND_BTN_CLASS = 'navbar-brand';

    /** @var array<string,Attribute> */
    protected array $attributes;

    /** @var Button */
    protected $brandBtn;

    /** @var ITag */
    protected $hamburgerBtn;

    /**
     * Header constructor.
     *
     * @param Button      $brandBtn
     * @param ITag|null   $hamburgerBtn
     * @param array       $attributes
     * @param string|null $tag
     */
    public function __construct(
        Button $brandBtn,
        ?ITag $hamburgerBtn = null,
        array $attributes = [],
        ?string $tag = null
    ) {
        parent::__construct(null, [], $attributes, $tag);

        $this->brandBtn     = $brandBtn->appendToAttribute(Html5::ATTR_CLASS, static::BRAND_BTN_CLASS);
        $this->hamburgerBtn = $hamburgerBtn ?: $this->createDefaultHamburgerBtn();

        $this->addAttribute(Html5::ATTR_CLASS, 'navbar-header');
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
        $content = $this->hamburgerBtn . $this->brandBtn;

        return TagHelper::toString($this->tag, $content, $this->attributes);
    }
}
