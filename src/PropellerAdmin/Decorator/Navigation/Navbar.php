<?php

declare(strict_types=1);

namespace AbterPhp\PropellerAdmin\Decorator\Navigation;

use AbterPhp\Admin\Constant\Routes;
use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Decorator\Decorator;
use AbterPhp\Framework\Decorator\Rule;
use AbterPhp\Framework\Html\Component;
use AbterPhp\Framework\Html\Component\Button;
use AbterPhp\Framework\Navigation\Navigation;
use AbterPhp\PropellerAdmin\Navigation\Header;
use Opulence\Routing\Urls\UrlGenerator;

class Navbar extends Decorator
{
    const NAVBAR_CONTAINER_CLASS = 'navbar navbar-inverse navbar-fixed-top pmd-navbar pmd-z-depth';
    const NAVBAR_CLASS           = 'container-fluid';

    const HEADER_WEIGHT = -100000;

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
            [Navigation::INTENT_NAVBAR],
            Navigation::class,
            [static::NAVBAR_CLASS],
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
        $navigation->setTag(Html5::TAG_DIV);

        $wrapperAttribs = [Html5::ATTR_CLASS => static::NAVBAR_CONTAINER_CLASS];
        $navigation->setWrapper(new Component('', [], $wrapperAttribs, Html5::TAG_NAV));

        // Add header
        $dashboardUrl = $this->urlGenerator->createFromName(Routes::ROUTE_DASHBOARD);
        $header       = new Header(new Button(null, [], [Html5::ATTR_HREF => $dashboardUrl], Html5::TAG_A));
        $navigation->addItem($header, static::HEADER_WEIGHT);
    }
}
