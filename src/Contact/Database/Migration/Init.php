<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Database\Migration;

use AbterPhp\Framework\Database\Migration\BaseMigration;
use DateTime;

class Init extends BaseMigration
{
    protected const FILENAME = 'contact.sql';

    /**
     * Gets the creation date, which is used for ordering
     *
     * @return DateTime The date this migration was created
     */
    public static function getCreationDate(): DateTime
    {
        return DateTime::createFromFormat(DATE_ATOM, '2019-02-28T21:03:00+00:00');
    }
}
