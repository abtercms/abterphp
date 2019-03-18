<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Action;

use AbterPhp\Framework\Html\Component\Button as HtmlButton;
use AbterPhp\Framework\Html\Component\IComponent;
use AbterPhp\Framework\I18n\ITranslator;
use Opulence\Orm\IEntity;

class Button extends HtmlButton implements IAction
{
    /** @var IEntity */
    protected $entity;

    /** @var array */
    protected $attributeCallbacks = [];

    /**
     * @param IComponent|string $content
     * @param array             $attributes
     * @param array             $attributeCallbacks
     * @param ITranslator|null  $translator
     * @param string|null       $tag
     */
    public function __construct(
        $content,
        array $attributes = [],
        array $attributeCallbacks = [],
        ITranslator $translator = null,
        ?string $tag = null
    ) {
        $this->attributeCallbacks = $attributeCallbacks;

        parent::__construct($content, $attributes, $translator, $tag);
    }

    /**
     * @return \Closure
     */
    public static function getDefaultCallback(): \Closure
    {
        return function ($attribute, IEntity $entity) {
            return sprintf($attribute, $entity->getId());
        };
    }

    /**
     * @param IEntity $entity
     */
    public function setEntity(IEntity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        foreach ($this->attributeCallbacks as $attribute => $callback) {
            $this->attributes[$attribute] = $callback($this->attributes[$attribute], $this->entity);
        }

        return parent::__toString();
    }

    /**
     * @return IAction
     */
    public function duplicate(): IAction
    {
        return new Button($this->content, $this->attributes, $this->attributeCallbacks, $this->translator, $this->tag);
    }
}
