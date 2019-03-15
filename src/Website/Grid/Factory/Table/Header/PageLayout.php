<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Factory\Table\Header;

use AbterPhp\Framework\Grid\Factory\Table\Header;

class PageLayout extends Header
{
    const GROUP_ID         = 'pageLayout-id';
    const GROUP_IDENTIFIER = 'pageLayout-identifier';
    const GROUP_TITLE      = 'pageLayout-title';

    const HEADER_ID         = 'pages:pageLayoutId';
    const HEADER_IDENTIFIER = 'pages:pageLayoutIdentifier';

    /** @var array */
    protected $headers = [
        self::GROUP_ID         => self::HEADER_ID,
        self::GROUP_IDENTIFIER => self::HEADER_IDENTIFIER,
    ];

    /** @var array */
    protected $inputNames = [
        self::GROUP_ID         => 'id',
        self::GROUP_IDENTIFIER => 'identifier',
        self::GROUP_TITLE      => 'title',
    ];

    /** @var array */
    protected $fieldNames = [
        self::GROUP_ID         => 'page_layouts.id',
        self::GROUP_IDENTIFIER => 'page_layouts.identifier',
        self::GROUP_TITLE      => 'page_layouts.title',
    ];
}
