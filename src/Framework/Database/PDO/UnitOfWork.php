<?php

namespace AbterPhp\Framework\Database\PDO;

use Opulence\Orm\OrmException;
use QB\Generic\Statement\Command;

class UnitOfWork
{
    protected Writer $writer;

    /**
     * UnitOfWork constructor.
     *
     * @param Writer $writer
     */
    public function __construct(Writer $writer)
    {
        $this->writer = $writer;
    }

    /**
     * Commits any entities that have been scheduled for insertion/updating/deletion
     *
     * @throws OrmException Thrown if there was an error committing the transaction
     */
    public function commit()
    {
        $this->writer->execute(new Command('COMMIT'));
    }

    /**
     * Disposes of all data in this unit of work
     */
    public function dispose()
    {
        $this->writer->execute(new Command('ROLLBACK'));
    }
}
