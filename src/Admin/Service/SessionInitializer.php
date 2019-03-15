<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Service;

use AbterPhp\Admin\Domain\Entities\User;
use Opulence\Sessions\ISession;

class SessionInitializer
{
    /** @var ISession */
    protected $session;

    /**
     * SessionInitializer constructor.
     *
     * @param ISession $session
     */
    public function __construct(ISession $session)
    {
        $this->session = $session;
    }

    /**
     * @param User $user
     */
    public function initialize(User $user)
    {
        if ($this->session->has(SESSION_USER_ID) && $this->session->get(SESSION_USER_ID) != $user->getId()) {
            return;
        }

        $this->session->set(SESSION_IS_LOGGED_IN, true);
        $this->session->set(SESSION_USER_ID, $user->getId());
        $this->session->set(SESSION_USERNAME, $user->getUsername());
        $this->session->set(SESSION_EMAIL, $user->getEmail());
        $this->session->set(SESSION_IS_GRAVATAR_ALLOWED, $user->isGravatarAllowed());
        $this->session->set(SESSION_LANGUAGE_IDENTIFIER, $user->getUserLanguage()->getIdentifier());
    }
}
