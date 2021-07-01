<?php

declare(strict_types=1);

namespace AbterPhp\Website\Template\Builder\ContentList;

use AbterPhp\Framework\Template\IBuilder;

class Simple extends Base implements IBuilder
{
    use ItemTrait;

    protected const IDENTIFIER = 'simple';

    protected string $defaultListClass = 'simple';

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return static::IDENTIFIER;
    }
}
