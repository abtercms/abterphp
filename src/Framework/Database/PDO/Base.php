<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Database\PDO;

use QB\Extra\PDOWrapper;

class Base extends PDOWrapper
{
    public const DIALECT_MYSQL = 'mysql';
    public const DIALECT_PGSQL = 'pgsql';

    protected string $dialect;

    /**
     * @param string $dialect
     *
     * @return $this
     */
    public function setDialect(string $dialect): static
    {
        $this->dialect = $dialect;

        return $this;
    }

    /**
     * @return string
     */
    public function getDialect(): string
    {
        return $this->dialect;
    }
}
