<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Table;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\Grid\Component\Body;
use AbterPhp\Framework\Grid\Component\Header;
use AbterPhp\Framework\Html\Component;
use AbterPhp\Framework\Html\Helper\StringHelper;
use AbterPhp\Framework\Html\INode;
use AbterPhp\Framework\Html\ITemplater;

class Table extends Component implements ITable, ITemplater
{
    /**
     *   %1$s - thead
     *   %2$s - tbody
     */
    const DEFAULT_TEMPLATE = '%1$s%2$s';

    const DEFAULT_TAG = self::TAG_TABLE;

    const TAG_TABLE = 'table';

    /** @var Header */
    protected $header;

    /** @var Body */
    protected $body;

    /** @var string */
    protected $template = self::DEFAULT_TEMPLATE;

    /**
     * Table constructor.
     *
     * @param Body     $body
     * @param Header   $header
     * @param string[] $intents
     * @param array    $attributes
     */
    public function __construct(Body $body, Header $header, array $intents = [], array $attributes = [])
    {
        $this->body   = $body;
        $this->header = $header;

        parent::__construct(null, $intents, $attributes);
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
     * @param string $template
     *
     * @return $this
     */
    public function setTemplate(string $template): INode
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return INode[]
     */
    public function getNodes(): array
    {
        return $this->getAllNodes(0);
    }

    /**
     * @param int $depth
     *
     * @return INode[]
     */
    public function getAllNodes(int $depth = -1): array
    {
        $nodes = [$this->header, $this->body];

        if ($depth !== 0) {
            $nodes = array_merge(
                $this->header->getAllNodes($depth - 1),
                $this->body->getAllNodes($depth - 1),
                $nodes
            );
        }

        return array_merge($nodes, parent::getAllNodes($depth));
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $thead = (string)$this->header;
        $tbody = (string)$this->body;

        $content = sprintf(
            $this->template,
            $thead,
            $tbody
        );

        $content = StringHelper::wrapInTag($content, $this->tag, $this->attributes);

        return $content;
    }
}
