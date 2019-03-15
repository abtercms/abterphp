<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Factory\Table;

use AbterPhp\Framework\Grid\Factory\Table;
use AbterPhp\Framework\Grid\Factory\Table\Body;
use AbterPhp\Website\Grid\Factory\Table\Header\Block as BlockHeaderFactory;

class Block extends Table
{
    /**
     * Block constructor.
     *
     * @param BlockHeaderFactory $headerFactory
     * @param Body               $bodyFactory
     */
    public function __construct(BlockHeaderFactory $headerFactory, Body $bodyFactory)
    {
        parent::__construct($headerFactory, $bodyFactory);
    }
}
