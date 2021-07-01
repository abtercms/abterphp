<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Events\Listeners;

use AbterPhp\Contact\Constant\Resource;
use AbterPhp\Contact\Constant\Route;
use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Events\NavigationReady;
use AbterPhp\Framework\Html\Attribute;
use AbterPhp\Framework\Html\Factory\Button as ButtonFactory;
use AbterPhp\Framework\Html\ITag;
use AbterPhp\Framework\Navigation\Dropdown;
use AbterPhp\Framework\Navigation\Item;
use AbterPhp\Framework\Navigation\Navigation;
use Opulence\Routing\Urls\UrlException;

class NavigationBuilder
{
    protected const BASE_WEIGHT = 900;

    protected ButtonFactory $buttonFactory;

    /**
     * NavigationRegistrar constructor.
     *
     * @param ButtonFactory $buttonFactory
     */
    public function __construct(ButtonFactory $buttonFactory)
    {
        $this->buttonFactory = $buttonFactory;
    }

    /**
     * @param NavigationReady $event
     *
     * @throws UrlException
     */
    public function handle(NavigationReady $event)
    {
        $navigation = $event->getNavigation();

        if (!$navigation->hasIntent(Navigation::INTENT_PRIMARY)) {
            return;
        }

        $item   = $this->createContactItem();

        $navigation->addWithWeight(static::BASE_WEIGHT, $item);
    }

    /**
     * @return Item
     * @throws \Opulence\Routing\Urls\UrlException
     */
    protected function createFormsItem(): Item
    {
        $text = 'contact:forms';
        $icon = 'assignment';

        $button   = $this->buttonFactory->createFromName($text, Route::CONTACT_FORMS_LIST, [], $icon);
        $resource = $this->getAdminResource(Resource::CONTACT_FORMS);

        $item = new Item($button);
        $item->setResource($resource);

        return $item;
    }

    /**
     * @return Item
     * @throws \Opulence\Routing\Urls\UrlException
     */
    protected function createContactItem(): Item
    {
        $text = 'contact:contact';
        $icon = 'contacts';

        $button   = $this->buttonFactory->createFromName($text, Route::CONTACT_FORMS_LIST, [], $icon);
        $resource = $this->getAdminResource(Resource::CONTACT_FORMS);

        $item = new Item($button);
        $item->setResource($resource);

        $item->setIntent(Item::INTENT_DROPDOWN);
        $item->setAttribute(new Attribute(Html5::ATTR_ID, 'nav-contact'));

        if (!empty($item[0]) && $item[0] instanceof ITag) {
            $item[0]->setAttribute(new Attribute(Html5::ATTR_HREF, 'javascript:void(0);'));
        }

        $item[1] = $this->createDropdown();

        return $item;
    }

    /**
     * @return Dropdown
     * @throws \Opulence\Routing\Urls\UrlException
     */
    protected function createDropdown(): Dropdown
    {
        $dropdown = new Dropdown();
        $dropdown[] = $this->createFormsItem();

        return $dropdown;
    }

    /**
     * @param string $resource
     *
     * @return string
     */
    protected function getAdminResource(string $resource): string
    {
        return sprintf('admin_resource_%s', $resource);
    }
}
