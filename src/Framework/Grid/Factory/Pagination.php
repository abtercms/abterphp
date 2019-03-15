<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Factory;

use AbterPhp\Framework\Grid\Pagination\Options;
use AbterPhp\Framework\Grid\Pagination\Pagination as Component;
use AbterPhp\Framework\I18n\ITranslator;

class Pagination
{
    /** @var Options */
    protected $options;

    /** @var ITranslator */
    protected $translator;

    /** @var int */
    protected $pageSize;

    /**
     * Pagination constructor.
     *
     * @param Options $options
     */
    public function __construct(Options $options, ITranslator $translator)
    {
        $this->options    = $options;
        $this->translator = $translator;

        $this->pageSize = $options->getDefaultPageSize();
    }

    /**
     * @param int $pageSize
     *
     * @return $this
     */
    public function setPageSize(int $pageSize): Pagination
    {
        $this->pageSize = $pageSize;

        return $this;
    }

    /**
     * @param array  $params
     * @param string $baseUrl
     *
     * @return Component
     */
    public function create(array $params, string $baseUrl): Component
    {
        return new Component(
            $params,
            $baseUrl,
            $this->options->getNumberCount(),
            $this->options->getDefaultPageSize(),
            $this->options->getPageSizeOptions(),
            $this->options->getAttributes(),
            $this->translator
        );
    }
}
