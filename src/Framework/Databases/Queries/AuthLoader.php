<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Databases\Queries;

interface AuthLoader
{
    /**
     * @return array|bool
     */
    public function loadAll();
}
