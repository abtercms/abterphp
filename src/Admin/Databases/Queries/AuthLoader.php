<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Databases\Queries;

interface AuthLoader
{
    /**
     * @return array|bool
     */
    public function loadAll();
}
