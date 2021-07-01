<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Action;

use AbterPhp\Framework\Html\Attribute;
use AbterPhp\Framework\Html\Component\Button;
use AbterPhp\Framework\Html\INode;
use InvalidArgumentException;
use LogicException;
use Opulence\Orm\IEntity;

class Action extends Button implements IAction
{
    protected ?IEntity $entity = null;

    /** @var array<string,callable> */
    protected array $attributeCallbacks = [];

    /**
     * Action constructor.
     *
     * @param INode[]|INode|string|null $content
     * @param string[]                  $intents
     * @param array<string,mixed>       $attributes
     * @param array<string,callable>    $attributeCallbacks
     * @param string|null               $tag
     */
    public function __construct(
        $content,
        array $intents = [],
        array $attributes = [],
        array $attributeCallbacks = [],
        ?string $tag = null
    ) {
        parent::__construct($content, $intents, $attributes, $tag);

        $this->setAttributeCallbacks($attributeCallbacks);
    }

    /**
     * @param array<string,callable> $callbacks
     *
     * @return $this
     */
    public function setAttributeCallbacks(array $callbacks): self
    {
        foreach (array_keys($callbacks) as $key) {
            if (!array_key_exists($key, $this->attributes)) {
                throw new InvalidArgumentException(
                    sprintf('Attribute callback for non-existent attribute: %s', $key)
                );
            }
        }

        $this->attributeCallbacks = $callbacks;

        return $this;
    }

    /**
     * @param IEntity $entity
     */
    public function setEntity(IEntity $entity): void
    {
        $this->entity = $entity;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        foreach ($this->attributeCallbacks as $key => $callback) {
            $value  = $this->attributes[$key]->getValue();
            $result = (array)$callback($value, $this->entity);

            $this->setAttribute(new Attribute($key, ...$result));
        }

        return parent::__toString();
    }

    /**
     * @param string $key
     *
     * @return $this
     */
    public function removeAttribute(string $key): self
    {
        if (array_key_exists($key, $this->attributeCallbacks)) {
            throw new LogicException(sprintf("Attribute is protected, can not be removed: %s", $key));
        }

        return parent::removeAttribute($key);
    }
}
