<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Domain\Entities;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class User implements IStringerEntity
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $username;

    /** @var string */
    protected $email;

    /** @var string */
    protected $password;

    /** @var UserGroup */
    protected $userGroup;

    /** @var UserLanguage */
    protected $userLanguage;

    /** @var bool */
    protected $canLogin;

    /** @var bool */
    protected $isGravatarAllowed;

    /**
     * User constructor.
     *
     * @param int          $id
     * @param string       $username
     * @param string       $email
     * @param string       $password
     * @param UserGroup    $userGroup
     * @param UserLanguage $userLanguage
     * @param bool         $canLogin
     * @param bool         $isGravatarAllowed
     */
    public function __construct(
        int $id,
        string $username,
        string $email,
        string $password,
        UserGroup $userGroup,
        UserLanguage $userLanguage,
        bool $canLogin = true,
        bool $isGravatarAllowed = true
    ) {
        $this->id                = $id;
        $this->username          = $username;
        $this->email             = $email;
        $this->password          = $password;
        $this->userGroup         = $userGroup;
        $this->userLanguage      = $userLanguage;
        $this->canLogin          = $canLogin;
        $this->isGravatarAllowed = $isGravatarAllowed;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername(string $username): User
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return UserGroup
     */
    public function getUserGroup(): UserGroup
    {
        return $this->userGroup;
    }

    /**
     * @param UserGroup $userGroup
     *
     * @return $this
     */
    public function setUserGroup(UserGroup $userGroup): User
    {
        $this->userGroup = $userGroup;

        return $this;
    }

    /**
     * @return UserLanguage
     */
    public function getUserLanguage(): UserLanguage
    {
        return $this->userLanguage;
    }

    /**
     * @param UserLanguage $userLanguage
     *
     * @return $this
     */
    public function setUserLanguage(UserLanguage $userLanguage): User
    {
        $this->userLanguage = $userLanguage;

        return $this;
    }

    /**
     * @return bool
     */
    public function canLogin(): bool
    {
        return $this->canLogin;
    }

    /**
     * @param bool $canLogin
     *
     * @return $this
     */
    public function setCanLogin(bool $canLogin): User
    {
        $this->canLogin = $canLogin;

        return $this;
    }

    /**
     * @return bool
     */
    public function isGravatarAllowed(): bool
    {
        return $this->isGravatarAllowed;
    }

    /**
     * @param bool $isGravatarAllowed
     *
     * @return $this
     */
    public function setIsGravatarAllowed(bool $isGravatarAllowed): User
    {
        $this->isGravatarAllowed = $isGravatarAllowed;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getUsername();
    }
}
