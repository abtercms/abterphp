<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Pagination;

use AbterPhp\Framework\Html\Component\Select;
use AbterPhp\Framework\Html\Component\Component;
use AbterPhp\Framework\I18n\ITranslator;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class Pagination extends Component implements IPagination
{
    const PARAM_KEY_PAGE = 'page';
    const PARAM_KEY_SIZE = 'page-size';

    const ERROR_MSG_INVALID_NUMBER_COUNT            = 'Number count must be a positive odd number.';
    const ERROR_MSG_INVALID_PAGE_SIZE               = 'Page size given is not allowed.';
    const ERROR_MSG_TOTAL_COUNT_NON_POSITIVE        = 'Total count must be a positive number.';
    const ERROR_MSG_TOTAL_COUNT_SMALLER_THAN_OFFSET = 'Offset must be smaller than total count.';

    const TEMPLATE = '<div class="gp-numbers col-md-6">%1$s</div><div class="gp-options col-md-6">%2$s%3$s</div>';

    /** @var array */
    protected $params = [];

    /** @var int */
    protected $rangeStart = 0;

    /** @var int */
    protected $rangeEnd = 0;

    /** @var int */
    protected $pageSize = 0;

    /** @var int */
    protected $totalCount = 0;

    /** @var int */
    protected $numberCount = 5;

    /** @var array */
    protected $pageSizes = [10, 50, 200];

    /** @var array */
    protected $attributes = [];

    /** @var Numbers */
    protected $numbers;

    /** @var Select */
    protected $sizeOptions;

    /**
     * Pagination constructor.
     *
     * @param array       $params
     * @param string      $baseUrl
     * @param int         $numberCount
     * @param int         $pageSize
     * @param array       $pageSizes
     * @param array       $attributes
     * @param ITranslator $translator
     */
    public function __construct(
        array $params,
        string $baseUrl,
        int $numberCount,
        int $pageSize,
        array $pageSizes,
        array $attributes,
        ITranslator $translator
    ) {
        $this->params      = $params;
        $this->pageSize    = $pageSize;
        $this->numberCount = $numberCount;
        $this->translator  = $translator;

        $this->setParams($params);

        $this->checkArguments($pageSizes);

        $this->buildComponents($baseUrl, $pageSizes);

        parent::__construct('', 'div', $attributes, $translator);

        $this->appendToAttribute(Component::ATTRIBUTE_CLASS, 'grid-pagination row');
    }

    /**
     * @param array $params
     */
    protected function setParams(array $params)
    {
        $page = 1;
        if (array_key_exists(static::PARAM_KEY_PAGE, $params)) {
            $page = $params[static::PARAM_KEY_PAGE];
        }

        if (array_key_exists(static::PARAM_KEY_SIZE, $params)) {
            $this->pageSize = (int)$params[static::PARAM_KEY_SIZE];
        }

        $this->rangeStart = ($page - 1) * $this->pageSize;
    }

    /**
     * @param array $pageSizes
     */
    protected function checkArguments(array $pageSizes)
    {
        if ($this->numberCount % 2 !== 1 || $this->numberCount < 1) {
            throw new \InvalidArgumentException(static::ERROR_MSG_INVALID_NUMBER_COUNT);
        }
        if (!in_array($this->pageSize, $pageSizes)) {
            throw new \InvalidArgumentException(static::ERROR_MSG_INVALID_PAGE_SIZE);
        }
    }

    /**
     * @param string $baseUrl
     * @param array  $pageSizes
     */
    protected function buildComponents(string $baseUrl, array $pageSizes)
    {
        $baseUrl    = $this->getPageSizeUrl($baseUrl);
        $pageSizes  = array_combine($pageSizes, $pageSizes);
        $attributes = [
            Select::ATTRIBUTE_CLASS => 'pagination-sizes',
        ];

        $this->numbers     = new Numbers($baseUrl);
        $this->sizeOptions = new Select(
            'pagination-sizes',
            $pageSizes,
            (string)$this->pageSize,
            Select::TAG_SELECT,
            $attributes
        );
    }

    /**
     * @param string $baseUrl
     *
     * @return string
     */
    public function getPageSizeUrl(string $baseUrl): string
    {
        return $baseUrl . sprintf('%s=%d&', static::PARAM_KEY_SIZE, $this->pageSize);
    }

    /**
     * @param string $baseUrl
     *
     * @return string
     */
    public function setSortedUrl(string $baseUrl): IPagination
    {
        $this->numbers->setBaseUrl($baseUrl);

        return $this;
    }

    /**
     * @return int
     */
    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    /**
     * @param int $totalCount
     *
     * @return $this
     */
    public function setTotalCount(int $totalCount): IPagination
    {
        if ($totalCount < 0) {
            throw new \InvalidArgumentException(static::ERROR_MSG_TOTAL_COUNT_NON_POSITIVE);
        }
        if ($this->rangeStart && $this->rangeStart > $totalCount) {
            throw new \InvalidArgumentException(static::ERROR_MSG_TOTAL_COUNT_SMALLER_THAN_OFFSET);
        }

        $this->totalCount = $totalCount;
        $this->rangeEnd   = min($this->totalCount, $this->rangeStart + $this->pageSize - 1);

        $currentPage = $this->getCurrentPage();
        $pageNumbers = $this->getPageNumbers($currentPage);
        $lastPage    = (int)ceil($totalCount / $this->pageSize);

        $this->numbers->populate($currentPage, $pageNumbers, $lastPage);

        return $this;
    }

    /**
     * @return int
     */
    protected function getRangeEnd(): int
    {
        return min($this->totalCount, $this->rangeStart + $this->pageSize - 1);
    }

    /**
     * @return int
     */
    protected function getCurrentPage(): int
    {
        $currentPage = (int)floor($this->rangeStart / $this->pageSize) + 1;

        return $currentPage;
    }

    /**
     * @return int
     */
    protected function getMinPageNumber(int $currentPage): int
    {
        $minPage = (int)($currentPage - floor($this->numberCount / 2));
        $result  = (int)max($minPage, 1);

        return $result;
    }

    /**
     * @return int
     */
    protected function getMaxPageNumber(int $currentPage): int
    {
        $maxPage = (int)($currentPage + floor($this->numberCount / 2));
        $result  = (int)min($maxPage, max(ceil($this->totalCount / $this->pageSize), 1));

        return $result;
    }

    /**
     * @param int $currentPage
     *
     * @return int[]
     */
    protected function getPageNumbers(int $currentPage): array
    {
        $minPageNumber = $this->getMinPageNumber($currentPage);
        $maxPageNumber = $this->getMaxPageNumber($currentPage);

        $numbers = [];
        for ($i = 0; ($i < $this->numberCount) && ($minPageNumber + $i <= $maxPageNumber); $i++) {
            $numbers[] = $minPageNumber + $i;
        }

        return $numbers;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $numbers     = (string)$this->numbers;
        $sizeLabel   = $this->translator->translate('framework:pageSize');
        $sizeOptions = (string)$this->sizeOptions;

        $this->content = sprintf(static::TEMPLATE, $numbers, $sizeLabel, $sizeOptions);

        return parent::__toString();
    }
}
