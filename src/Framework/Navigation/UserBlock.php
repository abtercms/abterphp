<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Navigation;

use AbterPhp\Admin\Constant\Routes;
use AbterPhp\Framework\Constant\Session;
use AbterPhp\Framework\I18n\ITranslator;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;

class UserBlock extends Item
{
    const DEFAULT_USER_IMAGE = '<img src="/admin-assets/themes/images/user-icon.png" alt="%s">';

    /** @var ISession */
    protected $session;

    /** @var ITranslator */
    protected $translator;

    /** @var UrlGenerator */
    protected $urlGenerator;

    /**
     * NavigationRegistrar constructor.
     *
     * @param ISession     $session
     * @param ITranslator  $translator
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(ISession $session, ITranslator $translator, UrlGenerator $urlGenerator)
    {
        $this->session      = $session;
        $this->translator   = $translator;
        $this->urlGenerator = $urlGenerator;

        $content   = sprintf('%1$s%2$s', $this->getUserBlock(), $this->getDropdown());
        $attr = [
            Item::ATTRIBUTE_CLASS => 'dropdown pmd-dropdown pmd-user-info visible-xs visible-md visible-sm visible-lg',
        ];

        parent::__construct($content, static::TAG_LI, $attr);
    }

    /**
     * @return string
     */
    protected function getUserBlock(): string
    {
        $str = '
            <a aria-expanded="false" data-toggle="dropdown" class="btn-user dropdown-toggle media" data-sidebar="true">
                <div class="media-left">%1$s</div>
                <div class="media-body media-middle">%2$s</div>
                <div class="media-right media-middle"><i class="dic-more-vert dic"></i></div>
            </a>
            ';

        return sprintf(
            $str,
            $this->getUserImage(),
            (string)$this->session->get(Session::USERNAME)
        );
    }

    /**
     * @return string
     */
    protected function getUserImage(): string
    {
        if (!$this->session || !$this->session->has(Session::USERNAME)) {
            return sprintf(static::DEFAULT_USER_IMAGE, '');
        }

        if (!$this->session->has(Session::EMAIL) || !$this->session->get(Session::IS_GRAVATAR_ALLOWED)) {
            return sprintf(static::DEFAULT_USER_IMAGE, $this->session->get(Session::USERNAME));
        }

        $str = '
                    <div class="user-img" style="background: url(https://www.gravatar.com/avatar/%1$s) no-repeat;">
                        <img src="https://www.gravatar.com/avatar/%1$s" alt="%1$s">
                    </div>
                    ';

        return sprintf(
            $str,
            md5((string)$this->session->get(Session::EMAIL)),
            (string)$this->session->get(Session::USERNAME)
        );
    }

    /**
     * @return string
     * @throws \Opulence\Routing\Urls\URLException
     */
    protected function getDropdown(): string
    {
        if (!$this->session || !$this->session->has(Session::USERNAME)) {
            return '';
        }

        $str = '
            <div class="pmd-dropdown-menu-container">
                <div class="pmd-dropdown-menu-bg"></div>
                <ul class="dropdown-menu">
                    <li><a href="%1$s">%2$s</a></li>
                </ul>
            </div>
                    ';

        return sprintf(
            $str,
            $this->urlGenerator->createFromName(Routes::ROUTE_LOGOUT),
            (string)$this->translator->translate('framework:logout')
        );
    }
}
