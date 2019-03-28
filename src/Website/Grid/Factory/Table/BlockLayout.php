<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Factory\Table;

use AbterPhp\Framework\Grid\Factory\TableFactory;
use AbterPhp\Framework\Grid\Factory\Table\BodyFactory;
use AbterPhp\Website\Grid\Factory\Table\Header\BlockLayout as HeaderFactory;

class BlockLayout extends TableFactory
{
    /**
     * BlockLayout constructor.
     *
     * @param HeaderFactory $headerFactory
     * @param BodyFactory   $bodyFactory
     */
    public function __construct(HeaderFactory $headerFactory, BodyFactory $bodyFactory)
    {
        parent::__construct($headerFactory, $bodyFactory);
    }
}
