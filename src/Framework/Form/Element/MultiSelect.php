<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Element;

use AbterPhp\Framework\Constant\Html5;

class MultiSelect extends Select
{
    protected const ERROR_NO_CONTENT = 'MultiSelect can not contain nodes';

    /**
     * MultiSelect constructor.
     *
     * @param string              $inputId
     * @param string              $name
     * @param string[]            $intents
     * @param array<string,mixed> $attributes
     * @param string|null         $tag
     */
    public function __construct(
        string $inputId,
        string $name,
        array $intents = [],
        array $attributes = [],
        ?string $tag = null
    ) {
        parent::__construct($inputId, $name, $intents, $attributes, $tag);

        $this->addAttribute(Html5::ATTR_MULTIPLE);
    }

    /**
     * @suppress PhanParamSignatureMismatch
     *
     * @return string[]
     */
    public function getValue()
    {
        $values = [];
        foreach ($this->content as $option) {
            if ($option->hasAttribute(Html5::ATTR_SELECTED)) {
                $values[] = $option->getValue();
            }
        }

        return $values;
    }

    /**
     * @param string|string[] $value
     *
     * @return $this
     */
    public function setValue($value): self
    {
        if (!is_array($value)) {
            throw new \InvalidArgumentException();
        }

        foreach ($value as $v) {
            if (!is_string($v)) {
                throw new \InvalidArgumentException();
            }
        }

        return $this->setValueInner($value);
    }
}
