<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Domain\Entities;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class AdminResource implements IStringerEntity
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $identifier;

    /**
     * Block constructor.
     *
     * @param int    $id
     * @param string $identifier
     */
    public function __construct(
        $id,
        string $identifier
    ) {
        $this->id         = $id;
        $this->identifier = $identifier;
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
    public function setIdentifier(string $identifier): Block
    {
        $this->identifier = $identifier;

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
