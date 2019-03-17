<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Http\Controllers\Admin;

use AbterPhp\Framework\Constant\Session;
use Opulence\Routing\Urls\URLException;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
trait UrlTrait
{
    /**
     * @return string
     * @throws URLException
     */
    protected function getShowUrl(): string
    {
        /** @var ISession $session */
        $session = $this->session;

        if ($session->has(Session::LAST_GRID_URL)) {
            return (string)$session->get(Session::LAST_GRID_URL);
        }

        /** @var UrlGenerator $urlGenerator */
        $urlGenerator = $this->urlGenerator;

        $url = $urlGenerator->createFromName(strtolower(static::ENTITY_PLURAL));

        return $url;
    }

    /**
     * @param int $id
     *
     * @return string
     * @throws URLException
     */
    protected function getEditUrl(int $id): string
    {
        /** @var UrlGenerator $urlGenerator */
        $urlGenerator = $this->urlGenerator;

        $url = $urlGenerator->createFromName(sprintf(static::URL_EDIT, strtolower(static::ENTITY_PLURAL)), $id);

        return $url;
    }
}
