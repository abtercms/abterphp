<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Html\Helper\StringHelper;

/**
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
class Component extends Collection implements IComponent
{
    const ERROR_INVALID_INTENT    = 'intent must be a string';
    const ERROR_INVALID_ATTRIBUTE = 'invalid attribute';

    const DEFAULT_TAG = Html5::TAG_SPAN;

    const INTENT_HIDDEN = 'hidden';

    const CLASS_HIDDEN = 'hidden';

    use TagTrait;

    /**
     * Component constructor.
     *
     * @param INode[]|INode|string|null $content
     * @param string[]                  $intents
     * @param array                     $attributes
     * @param string|null               $tag
     */
    public function __construct($content = null, array $intents = [], array $attributes = [], ?string $tag = null)
    {
        parent::__construct($content, $intents);

        $this->appendToAttributes($attributes);
        $this->setTag($tag);
    }

    /**
     * Return the key of the node if it is found, comparison uses spl_object_id
     *
     * @param INode $node
     *
     * @return int|null
     */
    public function find(INode $node): ?int
    {
        return $this->findNodeKey($node);
    }

    /**
     * Tries to find the first child that matches the arguments provided
     *
     * @param string|null $className
     * @param string      ...$intents
     *
     * @return IComponent|null
     */
    public function findFirstChild(?string $className = null, string ...$intents): ?IComponent
    {
        foreach ($this->nodes as $node) {
            if (!($node instanceof IComponent)) {
                continue;
            }

            if (!$node->isMatch($className, ...$intents)) {
                continue;
            }

            return $node;
        }

        return null;
    }

    /**
     * Collects all children, grandchildren, etc that match the arguments provided
     *
     * @param string|null $className
     * @param array       $intents
     * @param int         $depth maximum level of recursion, -1 or smaller means infinite, 0 is direct children only
     *
     * @return IComponent[]
     */
    public function collect(?string $className = null, array $intents = [], int $depth = -1): array
    {
        $components = [];

        foreach ($this->nodes as $node) {
            if ($node instanceof IComponent) {
                if (!$node->isMatch($className, ...$intents)) {
                    continue;
                }

                $components[] = $node;
            }

            if ($depth !== 0 && $node instanceof ICollection) {
                $childrenComponents = $this->getChildComponents($node, $depth - 1);
                foreach ($childrenComponents as $childComponent) {
                    if ($childComponent->isMatch($className, ...$intents)) {
                        $components[] = $childComponent;
                    }
                    $components = array_merge($components, $childComponent->collect($className, $intents, 0));
                }
            }
        }

        return $components;
    }

    /**
     * @param ICollection $collection
     * @param int         $depth
     *
     * @return IComponent[]
     */
    protected function getChildComponents(ICollection $collection, int $depth): array
    {
        $childComponents = [];
        $allNodes        = $collection->getAllNodes($depth);
        foreach ($allNodes as $node) {
            if ($node instanceof IComponent) {
                $childComponents[] = $node;
            }
        }

        return $childComponents;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $content = parent::__toString();

        $content = $this->translate($content);

        $content = StringHelper::wrapInTag($content, $this->tag, $this->attributes);

        return $content;
    }

    /**
     * @param mixed $content
     *
     * @return string
     */
    protected function translate($content): string
    {
        if (is_string($content) && $this->translator && $this->translator->canTranslate($content)) {
            return $this->translator->translate($content);
        }

        return (string)$content;
    }
}
