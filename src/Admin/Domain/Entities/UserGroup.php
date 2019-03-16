<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Domain\Entities;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class UserGroup implements IStringerEntity
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $identifier;

    /** @var string */
    protected $name;

    /** @var AdminResource[] */
    protected $adminResources;

    /**
     * UserGroup constructor.
     *
     * @param int    $id
     * @param string $identifier
     * @param string $name
     * @param array  $adminResources
     */
    public function __construct(
        int $id,
        string $identifier,
        string $name,
        array $adminResources = []
    ) {
        $this->id             = $id;
        $this->identifier     = $identifier;
        $this->name           = $name;

        $this->setAdminResources($adminResources);
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
    public function setIdentifier(string $identifier): UserGroup
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): UserGroup
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return AdminResource[]
     */
    public function getAdminResources(): array
    {
        return $this->adminResources;
    }

    /**
     * @param AdminResource[] $adminResources
     *
     * @return $this
     */
    public function setAdminResources(array $adminResources): UserGroup
    {
        foreach ($adminResources as $adminResource) {
            if (!($adminResource instanceof AdminResource)) {
                throw new \InvalidArgumentException();
            }
        }

        $this->adminResources = $adminResources;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName();
    }
}
