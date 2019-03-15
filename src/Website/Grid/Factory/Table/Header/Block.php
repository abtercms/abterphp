<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Factory\Table\Header;

use AbterPhp\Framework\Grid\Factory\Table\Header;

class Block extends Header
{
    const GROUP_ID         = 'block-id';
    const GROUP_IDENTIFIER = 'block-identifier';
    const GROUP_TITLE      = 'block-title';

    const HEADER_ID         = 'pages:blockId';
    const HEADER_IDENTIFIER = 'pages:blockIdentifier';
    const HEADER_TITLE      = 'pages:blockTitle';

    /** @var array */
    protected $headers = [
        self::GROUP_ID         => self::HEADER_ID,
        self::GROUP_IDENTIFIER => self::HEADER_IDENTIFIER,
        self::GROUP_TITLE      => self::HEADER_TITLE,
    ];

    /** @var array */
    protected $inputNames = [
        self::GROUP_ID         => 'id',
        self::GROUP_IDENTIFIER => 'identifier',
        self::GROUP_TITLE      => 'title',
    ];

    /** @var array */
    protected $fieldNames = [
        self::GROUP_ID         => 'blocks.id',
        self::GROUP_IDENTIFIER => 'blocks.identifier',
        self::GROUP_TITLE      => 'blocks.title',
    ];
}
