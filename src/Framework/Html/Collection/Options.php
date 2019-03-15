<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html\Collection;

use AbterPhp\Framework\Html\Component\Option;
use InvalidArgumentException;
use LogicException;

class Options extends Collection
{
    const TAG_HEAD = 'thead';

    /** @var Option[] */
    protected $components = [];

    /**
     * @return Option
     * @throws LogicException
     */
    public function current()
    {
        /** @var Option $object */
        $object = parent::current();

        $this->verifyReturn($object, Option::class);

        return $object;
    }

    /**
     * @param int|null $offset
     * @param Option   $value
     *
     * @throws InvalidArgumentException
     */
    public function offsetSet($offset, $value)
    {
        $this->verifyArgument($value, Option::class);

        parent::offsetSet($offset, $value);
    }

    /**
     * @param int $offset
     *
     * @return Option|null
     * @throws LogicException
     */
    public function offsetGet($offset)
    {
        /** @var Option $object */
        $object = parent::offsetGet($offset);

        $this->verifyReturn($object, Option::class);

        return $object;
    }
}
