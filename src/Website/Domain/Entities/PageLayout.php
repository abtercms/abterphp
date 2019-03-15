<?php

declare(strict_types=1);

namespace AbterPhp\Website\Domain\Entities;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Website\Domain\Entities\PageLayout\Assets;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class PageLayout implements IStringerEntity
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $identifier;

    /** @var string */
    protected $body;

    /** @var Assets|null */
    protected $assets;

    /**
     * Page constructor.
     *
     * @param int|null $id
     * @param string   $identifier
     * @param string   $body
     * @param Assets|null   $assets
     */
    public function __construct($id, string $identifier, string $body, ?Assets $assets)
    {
        $this->id         = $id;
        $this->identifier = $identifier;
        $this->body       = $body;
        $this->assets     = $assets;
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
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     *
     * @return $this
     */
    public function setIdentifier(string $identifier): PageLayout
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     *
     * @return $this
     */
    public function setBody(string $body): PageLayout
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return Assets|null
     */
    public function getAssets(): ?Assets
    {
        return $this->assets;
    }

    /**
     * @param Assets|null $assets
     *
     * @return $this
     */
    public function setAssets(?Assets $assets): PageLayout
    {
        $this->assets = $assets;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getIdentifier();
    }
}
