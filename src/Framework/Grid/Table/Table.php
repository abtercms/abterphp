<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Table;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\Grid\Collection\Body;
use AbterPhp\Framework\Grid\Collection\Header;
use AbterPhp\Framework\Grid\Collection\Rows;
use AbterPhp\Framework\Html\Component\Tag;
use AbterPhp\Framework\I18n\ITranslator;

class Table extends Tag implements ITable
{
    /**
     *   %1$s - thead - rows
     *   %2$s - tbody - headers
     */
    const TEMPLATE_CONTENT = '%1$s%2$s';

    const DEFAULT_TAG = self::TAG_TABLE;

    const TAG_TABLE = 'table';

    /** @var Header */
    protected $header;

    /** @var Body */
    protected $body;

    /**
     * Table constructor.
     *
     * @param Rows             $body
     * @param Rows             $header
     * @param array            $attributes
     * @param ITranslator|null $translator
     */
    public function __construct(Rows $body, Rows $header, array $attributes = [], ITranslator $translator = null)
    {
        $this->body   = $body;
        $this->header = $header;

        parent::__construct('', $attributes, $translator);
    }

    /**
     * @param string $baseUrl
     *
     * @return string
     */
    public function getSortedUrl(string $baseUrl): string
    {
        return $this->header->getSortedUrl($baseUrl);
    }

    /**
     * @return array
     */
    public function getSortConditions(): array
    {
        return $this->header->getSortConditions();
    }

    /**
     * @return array
     */
    public function getSqlParams(): array
    {
        return $this->header->getQueryParams();
    }

    /**
     * @param IStringerEntity[] $entities
     *
     * @return $this
     */
    public function setEntities(array $entities): ITable
    {
        $this->body->setEntities($entities);

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $thead = (string)$this->header;
        $tbody = (string)$this->body;

        $this->content = sprintf(
            static::TEMPLATE_CONTENT,
            $thead,
            $tbody
        );

        return parent::__toString();
    }
}
