<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Factory\Table\Header;

use AbterPhp\Admin\Grid\Factory\Table\HeaderFactory;

class BlockLayout extends HeaderFactory
{
    public const GROUP_NAME = 'blockLayout-name';

    private const HEADER_NAME = 'website:blockLayoutName';

    /** @var array<string,string> */
    protected array $headers = [
        self::GROUP_NAME => self::HEADER_NAME,
    ];

    /** @var array<string,string> */
    protected array $inputNames = [
        self::GROUP_NAME => 'name',
    ];

    /** @var array<string,string> */
    protected array $fieldNames = [
        self::GROUP_NAME => 'block_layouts.name',
    ];
}
