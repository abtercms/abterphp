<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Navigation;

use AbterPhp\Framework\Authorization\Constant\Role;
use AbterPhp\Framework\Html\Collection\Collection;
use AbterPhp\Framework\Html\Component\IComponent;
use AbterPhp\Framework\Html\Component\Tag;
use AbterPhp\Framework\I18n\ITranslator;
use Casbin\Enforcer;

class Navigation extends Collection
{
    const DEFAULT_TAG = self::TAG_UL;

    const TAG_NAV   = 'nav';
    const TAG_ASIDE = 'aside';
    const TAG_UL    = 'ul';

    const ERROR_INVALID_TAG_FOR_ITEM_CREATION = 'Item creation is not allowed for Navigation type: %s';

    /** @var string */
    protected $name;

    /** @var string */
    protected $username;

    /** @var IComponent|null lazy creation on getPrefix */
    protected $prefix;

    /** @var IComponent|null lazy creation on getPostfix */
    protected $postfix;

    /** @var Tag|null */
    protected $wrapper;

    /** @var Enforcer|null */
    protected $enforcer;

    /** @var Item[][] */
    protected $itemsByWeight = [];

    /** @var null|string */
    protected $tag = null;

    /** @var Item[] */
    protected $components = [];

    /** @var string */
    protected $componentClass = Item::class;

    /**
     * Navigation constructor.
     *
     * @param string        $name
     * @param ITranslator   $translator
     * @param string        $username
     * @param array         $attributes
     * @param Enforcer|null $enforcer
     * @param string|null   $tag
     */
    public function __construct(
        string $name,
        ITranslator $translator,
        string $username = '',
        array $attributes = [],
        ?Enforcer $enforcer = null,
        ?string $tag = null
    ) {
        $this->name         = $name;
        $this->username     = $username;
        $this->enforcer     = $enforcer;

        parent::__construct($attributes, $translator, $tag);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param Item $component
     * @param int        $weight
     * @param string     $resource
     * @param string     $role
     */
    public function addItem(
        Item $component,
        int $weight = PHP_INT_MAX,
        string $resource = '',
        string $role = Role::READ
    ) {
        if (!$this->isAllowed($resource, $role)) {
            return;
        }

        $this->itemsByWeight[$weight][] = $component;

        $this->resort();
    }

    /**
     * @param string $resource
     * @param string $role
     *
     * @return bool
     */
    protected function isAllowed(string $resource, string $role): bool
    {
        if (!$resource) {
            return true;
        }

        if (!$this->enforcer) {
            return false;
        }

        try {
            return (bool)$this->enforcer->enforce($this->username, $resource, $role);
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function resort()
    {
        ksort($this->itemsByWeight);

        $components = [];
        foreach ($this->itemsByWeight as $newComponents) {
            $components = array_merge($components, $newComponents);
        }

        $this->components = $components;
    }

    /**
     * @param int|null $offset
     * @param object   $value
     */
    public function offsetSet($offset, $value)
    {
        $this->itemsByWeight[PHP_INT_MAX][] = $value;

        parent::offsetSet($offset, $value);
    }

    /**
     * @return IComponent
     */
    public function getPrefix(): IComponent
    {
        if (null === $this->prefix) {
            $this->prefix = new Collection();
        }

        return $this->prefix;
    }

    /**
     * @param IComponent $prefix
     *
     * @return $this
     */
    public function setPrefix(IComponent $prefix): Navigation
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getPostfix(): Collection
    {
        if (null === $this->postfix) {
            $this->postfix = new Collection();
        }

        return $this->postfix;
    }

    /**
     * @param Collection $postfix
     *
     * @return $this
     */
    public function setPostfix(Collection $postfix): Navigation
    {
        $this->postfix = $postfix;

        return $this;
    }

    /**
     * @return Tag|null
     */
    public function getWrapper(): ?Tag
    {
        return $this->wrapper;
    }

    /**
     * @param Tag $wrapper
     *
     * @return $this
     */
    public function setWrapper(Tag $wrapper): Navigation
    {
        $this->wrapper = $wrapper;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $prefix = $this->prefix ? (string)$this->prefix : '';
        $main = parent::__toString();
        if ($this->wrapper) {
            $main = (string)$this->wrapper->setContent($main);
        }
        $postfix = $this->postfix ? (string)$this->postfix : '';

        return $prefix . $main . $postfix;
    }
}
