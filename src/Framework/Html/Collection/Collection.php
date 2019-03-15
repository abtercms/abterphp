<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html\Collection;

use AbterPhp\Framework\Html\Component\Component;
use AbterPhp\Framework\Html\Component\IComponent;
use AbterPhp\Framework\Html\Helper\StringHelper;
use AbterPhp\Framework\I18n\ITranslator;
use InvalidArgumentException;
use LogicException;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class Collection extends Component implements ICollection
{
    const ERROR_INVALID_TYPE_ARG     = 'Provided value must be an object instance of "%s", type "%s" is found';
    const ERROR_INVALID_INSTANCE_ARG = 'Provided value must be an instance of "%s", not an instance of "%s"';
    const ERROR_INVALID_TYPE_RETURN  = 'Retrieved value is not an instance of "%s"';

    /** @var int */
    protected $position = 0;

    /** @var IComponent[] */
    protected $components = [];

    /** @var string */
    protected $indentation = '';

    /**
     * Collection constructor.
     *
     * @param string|null      $tag
     * @param array            $attributes
     * @param ITranslator|null $translator
     */
    public function __construct(?string $tag = null, $attributes = [], ITranslator $translator = null)
    {
        $this->position = 0;

        parent::__construct('', $tag, $attributes, $translator);
    }

    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @return object
     */
    public function current()
    {
        $component = $this->components[$this->position];

        return $component;
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return isset($this->components[$this->position]);
    }

    /**
     * @param int|null $offset
     * @param object   $value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->components[] = $value;
        } else {
            $this->components[$offset] = $value;
        }
    }

    /**
     * @param int $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->components[$offset]);
    }

    /**
     * @param int $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->components[$offset]);
    }

    /**
     * @param int $offset
     *
     * @return object|null
     */
    public function offsetGet($offset)
    {
        return isset($this->components[$offset]) ? $this->components[$offset] : null;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->components);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $list = [];
        foreach ($this->components as $stringer) {
            $list[] = (string)$stringer;
        }

        $content = implode("\n" . $this->indentation, $list);

        $result = StringHelper::wrapInTag($content, $this->tag, $this->attributes);

        return $result;
    }

    /**
     * @param object $object
     * @param string $className
     *
     * @throws InvalidArgumentException
     */
    protected function verifyArgument($object, $className)
    {
        if ($object instanceof $className) {
            return;
        }

        $type = gettype($object);
        if (gettype($object) !== 'object') {
            throw new InvalidArgumentException(sprintf(static::ERROR_INVALID_TYPE_ARG, $className, $type));
        }


        throw new InvalidArgumentException(
            sprintf(static::ERROR_INVALID_INSTANCE_ARG, $className, get_class($object))
        );
    }

    /**
     * @param object $object
     * @param string $className
     *
     * @throws LogicException
     */
    protected function verifyReturn($object, $className)
    {
        if ($object instanceof $className) {
            return;
        }

        throw new LogicException(sprintf(static::ERROR_INVALID_TYPE_RETURN, $className));
    }
}
