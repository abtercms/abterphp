<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Factory\Table;

use AbterPhp\Framework\Grid\Factory\Table;
use AbterPhp\Framework\Grid\Factory\Table\Body;
use AbterPhp\Website\Grid\Factory\Table\Header\Page as HeaderFactory;

class Page extends Table
{
    /**
     * Page constructor.
     *
     * @param HeaderFactory $headerFactory
     * @param Body          $bodyFactory
     */
    public function __construct(HeaderFactory $headerFactory, Body $bodyFactory)
    {
        parent::__construct($headerFactory, $bodyFactory);
    }
}
