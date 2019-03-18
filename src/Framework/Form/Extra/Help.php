<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Extra;

use AbterPhp\Framework\Html\Component\Tag;

class Help extends Tag
{
    const DEFAULT_TAG = 'div';

    /** @var array */
    protected $attributes = [
        Help::ATTRIBUTE_CLASS => 'help-block'
    ];
}
