<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Factory;

use AbterPhp\Framework\Grid\Collection\Actions;
use AbterPhp\Framework\Grid\Factory\Table\Body as BodyFactory;
use AbterPhp\Framework\Grid\Factory\Table\Header as HeaderFactory;
use AbterPhp\Framework\Grid\Table\Table as Component;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;

class Table
{
    const ATTRIBUTE_CLASS = 'class';

    const ERROR_MSG_BODY_CREATED      = 'Grid table body is already created.';
    const ERROR_MSG_HEADER_CREATED    = 'Grid table header is already created.';
    const ERROR_MSG_TABLE_CREATED     = 'Grid table is already created.';
    const ERROR_MSG_NO_BODY_CREATED   = 'Grid table body is not yet created';
    const ERROR_MSG_NO_HEADER_CREATED = 'Grig table header is not yet created';

    /** @var HeaderFactory */
    protected $headerFactory;

    /** @var BodyFactory */
    protected $bodyFactory;

    /** @var array */
    protected $tableAttributes = [
        self::ATTRIBUTE_CLASS => 'table table-striped table-hover table-bordered',
    ];

    /** @var array */
    protected $headerAttributes = [];

    /** @var array */
    protected $bodyAttributes = [];

    /** @var bool */
    protected $componentCreated;

    /**
     * Table constructor.
     *
     * @param HeaderFactory $headerFactory
     * @param BodyFactory   $bodyFactory
     */
    public function __construct(HeaderFactory $headerFactory, BodyFactory $bodyFactory)
    {
        $this->headerFactory = $headerFactory;
        $this->bodyFactory   = $bodyFactory;
    }

    /**
     * @param IStringerEntity[] $entities
     * @param array             $getters
     * @param Actions|null      $rowActions
     * @param array             $headers
     * @param string            $baseUrl
     *
     * @return Component
     */
    public function create(
        array $getters,
        ?Actions $rowActions,
        array $headers,
        array $params,
        string $baseUrl
    ): Component {
        $hasActions = $rowActions && count($rowActions) > 0;

        $header = $this->headerFactory->create($headers, $hasActions, $params, $baseUrl);
        $body   = $this->bodyFactory->create($getters, $this->bodyAttributes, $rowActions);

        $this->componentCreated = true;

        return new Component($body, $header, $this->tableAttributes);
    }
}
