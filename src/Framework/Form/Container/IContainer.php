<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Container;

use AbterPhp\Framework\Form\Element\IElement;
use AbterPhp\Framework\Html\Component\IComponent;

interface IContainer extends IComponent
{
    /**
     * @return IElement[]
     */
    public function getElements(): array;

    /**
     * @param string $template
     *
     * @return $this
     */
    public function setTemplate(string $template): IContainer;
}
