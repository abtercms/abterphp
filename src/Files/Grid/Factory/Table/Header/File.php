<?php

declare(strict_types=1);

namespace AbterPhp\Files\Grid\Factory\Table\Header;

use AbterPhp\Framework\Grid\Factory\Table\Header;

class File extends Header
{
    const GROUP_ID          = 'file-id';
    const GROUP_FILENAME    = 'file-filename';
    const GROUP_CATEGORY    = 'file-category';
    const GROUP_DESCRIPTION = 'file-description';
    const GROUP_UPLOADED_AT = 'file-uploaded-at';

    const HEADER_ID          = 'files:fileId';
    const HEADER_PUBLIC_NAME = 'files:filePublicName';
    const HEADER_CATEGORY    = 'files:fileCategory';
    const HEADER_DESCRIPTION = 'files:fileDescription';
    const HEADER_UPLOADED_AT = 'files:fileUploadedAt';

    /** @var array */
    protected $headers = [
        self::GROUP_ID          => self::HEADER_ID,
        self::GROUP_FILENAME    => self::HEADER_PUBLIC_NAME,
        self::GROUP_CATEGORY    => self::HEADER_CATEGORY,
        self::GROUP_DESCRIPTION => self::HEADER_DESCRIPTION,
        self::GROUP_UPLOADED_AT => self::HEADER_UPLOADED_AT,
    ];

    /** @var array */
    protected $inputNames = [
        self::GROUP_ID       => 'id',
        self::GROUP_FILENAME => 'public-name',
    ];

    /** @var array */
    protected $fieldNames = [
        self::GROUP_ID       => 'files.id',
        self::GROUP_FILENAME => 'files.public_name',
    ];
}
