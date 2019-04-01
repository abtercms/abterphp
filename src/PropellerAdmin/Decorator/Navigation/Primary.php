<?php

declare(strict_types=1);

namespace AbterPhp\PropellerAdmin\Decorator\Navigation;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Decorator\Decorator;
use AbterPhp\Framework\Decorator\Rule;
use AbterPhp\Framework\Html\Component;
use AbterPhp\Framework\Html\ICollection;
use AbterPhp\Framework\Html\ITag;
use AbterPhp\Framework\Html\Tag;
use AbterPhp\Framework\Navigation\Dropdown;
use AbterPhp\Framework\Navigation\Item;
use AbterPhp\Framework\Navigation\Navigation;
use AbterPhp\Framework\Navigation\UserBlock;
use Opulence\Routing\Urls\UrlGenerator;

class Primary extends Decorator
{
    const PRIMARY_PREFIX_CLASS    = 'pmd-sidebar-overlay';
    const PRIMARY_CONTAINER_CLASS = 'pmd-sidebar sidebar-default pmd-sidebar-slide-push pmd-sidebar-left pmd-sidebar-open bg-fill-darkblue sidebar-with-icons nav pmd-sidebar-nav'; // nolint
    const PRIMARY_CLASS           = 'nav pmd-sidebar-nav';

    const USER_BLOCK_ITEM_CLASS = 'dropdown pmd-dropdown pmd-user-info visible-xs visible-md visible-sm visible-lg';

    const USER_BLOCK_CLASS         = 'btn-user dropdown-toggle media';
    const USER_BLOCK_ARIA_EXPANDED = 'false';
    const USER_BLOCK_DATA_TOGGLE   = 'dropdown';
    const USER_BLOCK_DATA_SIDEBAR  = 'true';

    const USER_BLOCK_MEDIA_LEFT_CLASS  = 'media-left';
    const USER_BLOCK_MEDIA_BODY_CLASS  = 'media-body media-middle';
    const USER_BLOCK_MEDIA_RIGHT_CLASS = 'media-right media-middle';

    const USER_BLOCK_MEDIA_RIGHT_ICON_CLASS = 'dic-more-vert dic';

    const DROPDOWN_WRAPPER_CLASS = 'pmd-dropdown-menu-container';
    const DROPDOWN_PREFIX_CLASS  = 'pmd-dropdown-menu-bg';
    const DROPDOWN_CLASS         = 'dropdown-menu';

    const HEADER_WEIGHT = -100000;

    const ATTR_ARIA_EXPANDED = 'aria-expanded';
    const ATTR_DATA_TOGGLE   = 'data-toggle';
    const ATTR_DATA_SIDEBAR  = 'data-sidebar';

    /** @var UrlGenerator */
    protected $urlGenerator;

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
        $navigation->setPrefix(new Component(null, [], [Html5::ATTR_CLASS => static::PRIMARY_PREFIX_CLASS]));

        $wrapperAttribs = [
            Html5::ATTR_CLASS => static::PRIMARY_CONTAINER_CLASS,
            Html5::ATTR_ROLE  => [Navigation::ROLE_NAVIGATION],
        ];
        $navigation->setWrapper(new Component(null, [], $wrapperAttribs, Html5::TAG_ASIDE));

        $this->handleButtons(...$navigation->getNodes());
        $this->handleUserGroup(...$navigation->getNodes());
    }

    /**
     * @param Item ...$items
     */
    protected function handleButtons(Item ...$items)
    {
        foreach ($items as $item) {
            /** @var Component\Button $button */
            foreach ($item->collect(Component\Button::class) as $button) {
                $button->unsetAttributeValue(Html5::ATTR_CLASS, 'btn');
            }
        }
    }

    /**
     * @param Item ...$items
     */
    protected function handleUserGroup(Item ...$items)
    {
        foreach ($items as $item) {
            if (!$item->hasIntent(UserBlock::class)) {
                continue;
            }

            $item->appendToClass(static::USER_BLOCK_ITEM_CLASS);

            foreach ($item as $subItem) {
                if ($subItem instanceof UserBlock) {
                    $this->decorateUserBlock($subItem);
                } elseif ($subItem instanceof Dropdown) {
                    $this->decorateDropdown($subItem);
                }
            }
        }
    }

    /**
     * @param UserBlock $userBlock
     */
    protected function decorateUserBlock(UserBlock $userBlock)
    {
        $userBlock->appendToClass(static::USER_BLOCK_CLASS)
            ->setAttribute(static::ATTR_ARIA_EXPANDED, static::USER_BLOCK_ARIA_EXPANDED)
            ->setAttribute(static::ATTR_DATA_SIDEBAR, static::USER_BLOCK_DATA_SIDEBAR)
            ->setAttribute(static::ATTR_DATA_TOGGLE, static::USER_BLOCK_DATA_TOGGLE);

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
            if ($right instanceof ICollection) {
                $right[] = new Tag(
                    null,
                    [],
                    [Html5::ATTR_CLASS => static::USER_BLOCK_MEDIA_RIGHT_ICON_CLASS],
                    Html5::TAG_I
                );
            }
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
