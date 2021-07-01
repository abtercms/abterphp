<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Factory\Table\Header;

use AbterPhp\Admin\Grid\Factory\Table\HeaderFactory;

class Block extends HeaderFactory
{
    public const GROUP_TITLE      = 'block-title';
    public const GROUP_IDENTIFIER = 'block-identifier';

    private const HEADER_TITLE      = 'website:blockTitle';
    private const HEADER_IDENTIFIER = 'website:blockIdentifier';

    /** @var array<string,string> */
    protected array $headers = [
        self::GROUP_TITLE      => self::HEADER_TITLE,
        self::GROUP_IDENTIFIER => self::HEADER_IDENTIFIER,
    ];

    /** @var array<string,string> */
    protected array $inputNames = [
        self::GROUP_TITLE      => 'title',
        self::GROUP_IDENTIFIER => 'identifier',
    ];

    /** @var array<string,string> */
    protected array $fieldNames = [
        self::GROUP_TITLE      => 'blocks.title',
        self::GROUP_IDENTIFIER => 'blocks.identifier',
    ];
}
