<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Factory\Table\Header;

use AbterPhp\Admin\Grid\Factory\Table\HeaderFactory;

class ContentList extends HeaderFactory
{
    public const GROUP_IDENTIFIER = 'contentList-identifier';
    public const GROUP_NAME       = 'contentList-name';

    private const HEADER_IDENTIFIER = 'website:contentListIdentifier';
    private const HEADER_NAME       = 'website:contentListName';

    /** @var array<string,string> */
    protected array $headers = [
        self::GROUP_IDENTIFIER => self::HEADER_IDENTIFIER,
        self::GROUP_NAME       => self::HEADER_NAME,
    ];

    /** @var array<string,string> */
    protected array $inputNames = [
        self::GROUP_IDENTIFIER => 'identifier',
        self::GROUP_NAME       => 'name',
    ];

    /** @var array<string,string> */
    protected array $fieldNames = [
        self::GROUP_IDENTIFIER => 'lists.identifier',
        self::GROUP_NAME       => 'lists.name',
    ];
}
