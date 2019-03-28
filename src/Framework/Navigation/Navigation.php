<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Navigation;

use AbterPhp\Framework\Authorization\Constant\Role;
use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Html\Collection;
use AbterPhp\Framework\Html\Helper\StringHelper;
use AbterPhp\Framework\Html\IComponent;
use AbterPhp\Framework\Html\INode;
use AbterPhp\Framework\Html\INodeContainer;
use AbterPhp\Framework\Html\Tag;
use AbterPhp\Framework\I18n\ITranslator;
use Casbin\Enforcer;

class Navigation extends Tag implements INodeContainer
{
    const DEFAULT_TAG = Html5::TAG_UL;

    const ERROR_INVALID_TAG_FOR_ITEM_CREATION = 'item creation is not allowed for navigation type: %s';

    const ROLE_NAVIGATION = 'navigation';

    const INTENT_NAVBAR    = 'navbar';
    const INTENT_FOOTER    = 'footer';
    const INTENT_PRIMARY   = 'primary';
    const INTENT_SECONDARY = 'secondary';

    /** @var string */
    protected $username;

    /** @var INode|null lazy creation on getPrefix */
    protected $prefix;

    /** @var INode|null lazy creation on getPostfix */
    protected $postfix;

    /** @var IComponent|null */
    protected $wrapper;

    /** @var Enforcer|null */
    protected $enforcer;

    /** @var Item[][] */
    protected $itemsByWeight = [];

    /** @var Item[] */
    protected $nodes;

    /**
     * Navigation constructor.
     *
     * @param string        $username
     * @param string[]      $intents
     * @param array         $attributes
     * @param Enforcer|null $enforcer
     * @param string|null   $tag
     */
    public function __construct(
        string $username = '',
        array $intents = [],
        array $attributes = [],
        ?Enforcer $enforcer = null,
        ?string $tag = null
    ) {
        $this->username = $username;
        $this->enforcer = $enforcer;

        parent::__construct(null, $intents, $attributes, $tag);
    }

    /**
     * @param Item   $component
     * @param int    $weight
     * @param string $resource
     * @param string $role
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

        $nodes = [];
        foreach ($this->itemsByWeight as $nodesByWeight) {
            $nodes = array_merge($nodes, $nodesByWeight);
        }

        $this->nodes = $nodes;
    }

    /**
     * @return Collection
     */
    public function getPrefix(): Collection
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
     * @return IComponent|null
     */
    public function getWrapper(): ?IComponent
    {
        return $this->wrapper;
    }

    /**
     * @param IComponent|null $wrapper
     *
     * @return $this
     */
    public function setWrapper(?IComponent $wrapper): Navigation
    {
        $this->wrapper = $wrapper;

        return $this;
    }

    /**
     * @return INode[]
     */
    public function getNodes(): array
    {
        return $this->getAllNodes(0);
    }

    /**
     * @param int $depth
     *
     * @return INode[]
     */
    public function getAllNodes(int $depth = -1): array
    {
        $nodes = [];
        foreach ($this->itemsByWeight as $nodesByWeight) {
            $nodes = array_merge($nodes, $nodesByWeight);

            if ($depth === 0) {
                continue;
            }

            foreach ($nodesByWeight as $node) {
                if (!($node instanceof INodeContainer)) {
                    $nodes = array_merge($nodes, $node->getAllNodes($depth - 1));
                }
            }
        }

        return $nodes;
    }

    /**
     * @deprecated
     *
     * @param string|INode
     *
     * @return $this
     */
    public function setContent($content): INode
    {
        if ($content !== null) {
            throw new \LogicException('Navigation::setContent must not be called');
        }

        return $this;
    }

    /**
     * @param ITranslator|null $translator
     *
     * @return $this
     */
    public function setTranslator(?ITranslator $translator): INode
    {
        $this->translator = $translator;

        $nodes = $this->getNodes();
        foreach ($nodes as $node) {
            $node->setTranslator($translator);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $this->resort();

        $prefix = $this->prefix ? (string)$this->prefix : '';

        $itemContentList = [];
        foreach ($this->nodes as $node) {
            $itemContentList[] = (string)$node;
        }
        $content = implode("\n", $itemContentList);

        $content = StringHelper::wrapInTag($content, $this->tag, $this->attributes);
        if ($this->wrapper) {
            $content = (string)$this->wrapper->setContent($content);
        }

        $postfix = $this->postfix ? (string)$this->postfix : '';

        return $prefix . $content . $postfix;
    }
}
