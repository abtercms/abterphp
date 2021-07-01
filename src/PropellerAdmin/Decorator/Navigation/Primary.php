<?php

declare(strict_types=1);

namespace AbterPhp\PropellerAdmin\Decorator\Navigation;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Decorator\Decorator;
use AbterPhp\Framework\Decorator\Rule;
use AbterPhp\Framework\Html\Attribute;
use AbterPhp\Framework\Html\Component\Button;
use AbterPhp\Framework\Html\INode;
use AbterPhp\Framework\Html\ITag;
use AbterPhp\Framework\Html\Tag;
use AbterPhp\Framework\Navigation\Dropdown;
use AbterPhp\Framework\Navigation\Item;
use AbterPhp\Framework\Navigation\Navigation;
use AbterPhp\Framework\Navigation\UserBlock;
use AbterPhp\PropellerAdmin\Decorator\General;
use Opulence\Routing\Urls\UrlGenerator;

class Primary extends Decorator
{
    public const USER_BLOCK_ITEM_CLASS = 'dropdown pmd-dropdown pmd-user-info visible-xs visible-md visible-sm visible-lg';

    public const DROPDOWN_WRAPPER_CLASS = 'pmd-dropdown-menu-container';
    public const DROPDOWN_CLASS         = 'dropdown-menu';
    public const PRIMARY_CLASS          = 'nav pmd-sidebar-nav';

    protected const PRIMARY_PREFIX_CLASS    = 'pmd-sidebar-overlay';
    protected const PRIMARY_CONTAINER_CLASS = 'pmd-sidebar sidebar-default pmd-sidebar-slide-push pmd-sidebar-left pmd-sidebar-open bg-fill-darkblue sidebar-with-icons nav pmd-sidebar-nav'; // nolint

    protected const USER_BLOCK_CLASS         = 'btn-user dropdown-toggle media';
    protected const USER_BLOCK_ARIA_EXPANDED = 'false';
    protected const USER_BLOCK_DATA_TOGGLE   = 'dropdown';
    protected const USER_BLOCK_DATA_SIDEBAR  = 'true';

    protected const USER_BLOCK_MEDIA_LEFT_CLASS  = 'media-left';
    protected const USER_BLOCK_MEDIA_BODY_CLASS  = 'media-body media-middle';
    protected const USER_BLOCK_MEDIA_RIGHT_CLASS = 'media-right media-middle';

    protected const USER_BLOCK_MEDIA_RIGHT_ICON_CLASS = 'dic-more-vert dic';

    protected const DROPDOWN_PREFIX_CLASS    = 'pmd-dropdown-menu-bg';
    protected const DROPDOWN_CONTAINER_CLASS = 'dropdown pmd-dropdown openable nav-open';

    protected const ATTR_ARIA_EXPANDED = 'aria-expanded';
    protected const ATTR_DATA_TOGGLE   = 'data-toggle';
    protected const ATTR_DATA_SIDEBAR  = 'data-sidebar';

    /** @var UrlGenerator */
    protected UrlGenerator $urlGenerator;

    /**
     * Primary constructor.
     *
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @return $this
     */
    public function init(): Decorator
    {
        $this->rules[] = new Rule(
            [Navigation::INTENT_PRIMARY],
            Navigation::class,
            [static::PRIMARY_CLASS],
            [],
            [$this, 'decorateNavigation']
        );

        return $this;
    }

    /**
     * @param Navigation $navigation
     */
    public function decorateNavigation(Navigation $navigation)
    {
        // Setup navigation properly
        $navigation->setPrefix(new Tag(null, [], [Html5::ATTR_CLASS => static::PRIMARY_PREFIX_CLASS]));

        $wrapperAttribs = [
            Html5::ATTR_CLASS => static::PRIMARY_CONTAINER_CLASS,
            Html5::ATTR_ROLE  => [Navigation::ROLE_NAVIGATION],
        ];
        $navigation->setWrapper(new Tag(null, [], $wrapperAttribs, Html5::TAG_ASIDE));

        $nodes = $navigation->getNodes();

        $this->handleButtons(...$nodes);
        $this->handleItems(...$nodes);
    }

    /**
     * @param INode ...$items
     */
    protected function handleButtons(INode ...$items)
    {
        foreach ($items as $item) {
            if (!($item instanceof Item)) {
                continue;
            }

            /** @var Button $button */
            foreach ($item->findAll(Button::class) as $button) {
                assert($button instanceof Button);
                $button->getAttribute(Html5::ATTR_CLASS)->remove(General::BUTTON_CLASS);
            }
        }
    }

    /**
     * @param INode ...$items
     */
    protected function handleItems(INode ...$items)
    {
        foreach ($items as $item) {
            if (!($item instanceof Item)) {
                continue;
            }

            if ($item->hasIntent(UserBlock::class)) {
                $this->decorateUserBlockContainer($item);
            } else {
                $this->decorateGeneralContainer($item);
            }
        }
    }

    /**
     * @param Item $item
     */
    protected function decorateGeneralContainer(Item $item)
    {
        if (!$item->hasIntent(Item::INTENT_DROPDOWN)) {
            return;
        }

        $item->appendToClass(static::DROPDOWN_CONTAINER_CLASS);

        foreach ($item as $subItem) {
            if ($subItem instanceof Dropdown) {
                $this->decorateDropdown($subItem);
            }
        }
    }

    /**
     * @param Item $item
     */
    protected function decorateUserBlockContainer(Item $item)
    {
        $item->appendToClass(static::USER_BLOCK_ITEM_CLASS);

        foreach ($item as $subItem) {
            if ($subItem instanceof UserBlock) {
                $this->decorateUserBlock($subItem);
            } elseif ($subItem instanceof Dropdown) {
                $this->decorateDropdown($subItem);
            }
        }
    }

    /**
     * @suppress PhanUndeclaredMethod
     *
     * @param UserBlock $userBlock
     */
    protected function decorateUserBlock(UserBlock $userBlock)
    {
        $userBlock->appendToClass(static::USER_BLOCK_CLASS)
            ->setAttribute(new Attribute(static::ATTR_ARIA_EXPANDED, static::USER_BLOCK_ARIA_EXPANDED))
            ->setAttribute(new Attribute(static::ATTR_DATA_SIDEBAR, static::USER_BLOCK_DATA_SIDEBAR))
            ->setAttribute(new Attribute(static::ATTR_DATA_TOGGLE, static::USER_BLOCK_DATA_TOGGLE));

        $left  = $userBlock->getMediaLeft();
        $body  = $userBlock->getMediaBody();
        $right = $userBlock->getMediaRight();

        if ($left instanceof ITag) {
            $left->appendToClass(static::USER_BLOCK_MEDIA_LEFT_CLASS);
        }
        if ($body instanceof ITag) {
            $body->appendToClass(static::USER_BLOCK_MEDIA_BODY_CLASS);
        }
        if ($right instanceof ITag) {
            $right->appendToClass(static::USER_BLOCK_MEDIA_RIGHT_CLASS);
            $right->add(
                new Tag(
                    null,
                    [],
                    [Html5::ATTR_CLASS => static::USER_BLOCK_MEDIA_RIGHT_ICON_CLASS],
                    Html5::TAG_I
                )
            );
        }
    }

    /**
     * @param Dropdown $dropdown
     */
    protected function decorateDropdown(Dropdown $dropdown)
    {
        $dropdown->appendToClass(static::DROPDOWN_CLASS);

        if ($dropdown->getWrapper()) {
            $dropdown->getWrapper()->appendToClass(static::DROPDOWN_WRAPPER_CLASS);
        }

        $prefix   = $dropdown->getPrefix();
        $prefix[] = new Tag(null, [], [Html5::ATTR_CLASS => static::DROPDOWN_PREFIX_CLASS], Html5::TAG_DIV);
    }
}
