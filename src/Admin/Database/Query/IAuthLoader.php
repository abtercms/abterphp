<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Database\Query;

interface IAuthLoader
{
    /**
     * @return array
     */
    public function loadAll(): array;
}
