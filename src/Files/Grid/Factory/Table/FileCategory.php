<?php

declare(strict_types=1);

namespace AbterPhp\Files\Grid\Factory\Table;

use AbterPhp\Framework\Grid\Factory\Table;
use AbterPhp\Framework\Grid\Factory\Table\Body;
use AbterPhp\Files\Grid\Factory\Table\Header\FileCategory as HeaderFactory;

class FileCategory extends Table
{
    /**
     * FileCategory constructor.
     *
     * @param HeaderFactory $headerFactory
     * @param Body          $bodyFactory
     */
    public function __construct(HeaderFactory $headerFactory, Body $bodyFactory)
    {
        parent::__construct($headerFactory, $bodyFactory);
    }
}
