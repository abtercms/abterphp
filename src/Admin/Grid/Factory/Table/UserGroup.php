<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Grid\Factory\Table;

use AbterPhp\Framework\Grid\Factory\Table;
use AbterPhp\Framework\Grid\Factory\Table\Body;
use AbterPhp\Admin\Grid\Factory\Table\Header\UserGroup as HeaderFactory;

class UserGroup extends Table
{
    /**
     * UserGroup constructor.
     *
     * @param HeaderFactory $headerFactory
     * @param Body          $bodyFactory
     */
    public function __construct(HeaderFactory $headerFactory, Body $bodyFactory)
    {
        parent::__construct($headerFactory, $bodyFactory);
    }
}
