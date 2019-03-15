<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html\Component;

interface IComponent
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @param string $tag
     *
     * @return $this
     */
    public function setTag(string $tag): IComponent;

    /**
     * @return array
     */
    public function getAttributes(): array;

    /**
     * @param array $attributes
     *
     * @return $this
     */
    public function setAttributes(array $attributes = []): IComponent;

    /**
     * @return string
     */
    public function __toString(): string;
}
