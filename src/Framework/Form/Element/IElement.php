<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Element;

use AbterPhp\Framework\Html\Component\IComponent;

interface IElement extends IComponent
{
    /**
     * @param string $value
     *
     * @return $this
     */
    public function setValue(string $value): IElement;

    /**
     * @return $this
     */
    public function getName(): string;
}
