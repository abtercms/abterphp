<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Navigation;

use AbterPhp\Framework\Authorization\Constant\Role;
use AbterPhp\Framework\Html\Collection\Collection;
use AbterPhp\Framework\Html\Component\Button;
use AbterPhp\Framework\Html\Component\IComponent;
use AbterPhp\Framework\I18n\ITranslator;
use Casbin\Enforcer;
use Opulence\Routing\Urls\UrlGenerator;

class Navigation extends Collection
{
    const DEFAULT_TAG = self::TAG_UL;

    const TAG_NAV   = 'nav';
    const TAG_ASIDE = 'aside';
    const TAG_UL    = 'ul';

    const ERROR_INVALID_TAG_FOR_ITEM_CREATION = 'Item creation is not allowed for Navigation type: %s';

    /** @var string */
    protected $name;

    /** @var UrlGenerator */
    protected $urlGenerator;

    /** @var string */
    protected $username;

    /** @var Enforcer|null */
    protected $enforcer;

    /** @var IComponent[][] */
    protected $componentsByWeight = [];

    /** @var null|string */
    protected $tag = null;

    /**
     * Navigation constructor.
     *
     * @param string        $name
     * @param UrlGenerator  $urlGenerator
     * @param ITranslator   $translator
     * @param string        $username
     * @param array         $attributes
     * @param Enforcer|null $enforcer
     * @param string|null        $tag
     */
    public function __construct(
        string $name,
        UrlGenerator $urlGenerator,
        ITranslator $translator,
        string $username = '',
        array $attributes = [],
        ?Enforcer $enforcer = null,
        ?string $tag = null
    ) {
        $this->name         = $name;
        $this->urlGenerator = $urlGenerator;
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
     * @param IComponent $component
     * @param int        $weight
     * @param string     $resource
     * @param string     $role
     */
    public function addItem(
        IComponent $component,
        int $weight = PHP_INT_MAX,
        string $resource = '',
        string $role = Role::READ
    ) {
        if (!$this->isAllowed($resource, $role)) {
            return;
        }

        $this->componentsByWeight[$weight][] = $component;

        $this->resort();
    }

    /**
     * @param IComponent|string $content
     * @param string            $urlName
     * @param array             $urlArgs
     * @param int               $weight
     * @param string            $resource
     * @param string            $role
     * @param array             $attr
     * @param array             $linkAttr
     *
     * @return IComponent|null
     * @throws \Opulence\Routing\Urls\URLException
     */
    public function createFromName(
        $content,
        string $urlName,
        array $urlArgs,
        int $weight = PHP_INT_MAX,
        string $resource = '',
        string $role = Role::READ,
        array $attr = [],
        array $linkAttr = []
    ): ?IComponent {
        $url = call_user_func_array([$this->urlGenerator, 'createFromName'], array_merge((array)$urlName, $urlArgs));

        return $this->createFromUrl($content, $url, $weight, $resource, $role, $attr, $linkAttr);
    }

    /**
     * @param IComponent|string $content
     * @param string            $url
     * @param int               $weight
     * @param string            $resource
     * @param string            $role
     * @param array             $attr
     * @param array             $linkAttr
     *
     * @return IComponent|null
     */
    public function createFromUrl(
        $content,
        string $url,
        int $weight = PHP_INT_MAX,
        string $resource = '',
        string $role = Role::READ,
        array $attr = [],
        array $linkAttr = []
    ): ?IComponent {
        if (!$this->isAllowed($resource, $role)) {
            return null;
        }

        $linkAttr[Item::ATTRIBUTE_HREF] = $url;

        $component = null;
        switch ($this->tag) {
            case static::TAG_UL:
                $link      = new Button($content, $linkAttr, null, Button::TAG_A);
                $component = new Item($link, $attr, $this->translator);
                break;
        }

        if (is_null($component)) {
            throw new \LogicException(sprintf(static::ERROR_INVALID_TAG_FOR_ITEM_CREATION, $this->tag));
        }

        $this->componentsByWeight[$weight][] = $component;

        $this->resort();

        return $component;
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
        ksort($this->componentsByWeight);

        $components = [];
        foreach ($this->componentsByWeight as $newComponents) {
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
        $this->componentsByWeight[PHP_INT_MAX][] = $value;

        parent::offsetSet($offset, $value);
    }
}
