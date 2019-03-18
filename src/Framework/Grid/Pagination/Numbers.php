<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Pagination;

use AbterPhp\Framework\Grid\Action\Button;
use AbterPhp\Framework\Grid\Collection\Actions;

class Numbers extends Actions
{
    /** @var string */
    protected $baseUrl;

    /** @var array */
    protected $fakeBtnAttr = [
        Button::ATTRIBUTE_CLASS    => Button::CLASS_PRIMARY,
        Button::ATTRIBUTE_DISABLED => Button::ATTRIBUTE_DISABLED,
    ];

    /** @var array */
    protected $realBtnAttr = [
        Button::ATTRIBUTE_CLASS => Button::CLASS_PRIMARY,
    ];

    /**
     * Numbers constructor.
     *
     * @param string $baseUrl
     */
    public function __construct(string $baseUrl)
    {
        parent::__construct();

        $this->baseUrl = $baseUrl;
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * Numbers constructor.
     *
     * @param int   $currentPage
     * @param array $pageNumbers
     * @param int   $lastPage
     */
    public function populate(int $currentPage, array $pageNumbers, int $lastPage)
    {
        $this->buttons = new Actions();

        $lastNumber = $pageNumbers[count($pageNumbers) - 1];

        $isFirst        = ($currentPage === 1);
        $isLast         = ($currentPage == $lastNumber);
        $isFirstVisible = ($pageNumbers[0] === 1);
        $isLastVisible  = ($lastPage <= $lastNumber);

        $this->attachLeft($isFirst, $isFirstVisible, $currentPage);
        $this->attachNumbers($pageNumbers, $currentPage);
        $this->attachRight($isLast, $isLastVisible, $currentPage, $lastPage);
    }

    /**
     * @param bool $isFirstVisible
     * @param bool $isFirst
     * @param int  $currentPage
     */
    protected function attachLeft(bool $isFirst, bool $isFirstVisible, int $currentPage)
    {
        if (!$isFirstVisible) {
            $this->realBtnAttr[Button::ATTRIBUTE_HREF] = sprintf('%spage=%d', $this->baseUrl, 1);

            $this->components[] = new Button('<<', Button::TAG_A, $this->realBtnAttr);
        }

        if (!$isFirst) {
            $this->realBtnAttr[Button::ATTRIBUTE_HREF] = sprintf('%spage=%d', $this->baseUrl, $currentPage - 1);

            $this->components[] = new Button('<', Button::TAG_A, $this->realBtnAttr);
        }

        if (!$isFirstVisible) {
            $this->components[] = new Button('...', Button::TAG_BUTTON, $this->fakeBtnAttr);
        }
    }

    /**
     * @param int[] $numbers
     * @param int   $currentPage
     */
    protected function attachNumbers(array $numbers, int $currentPage)
    {
        foreach ($numbers as $number) {
            if ($currentPage == $number) {
                $this->components[] = new Button("$number", $this->fakeBtnAttr);
            } else {
                $this->realBtnAttr[Button::ATTRIBUTE_HREF] = sprintf('%spage=%d', $this->baseUrl, $number);

                $this->components[] = new Button("$number", $this->realBtnAttr, [], null, Button::TAG_A);
            }
        }
    }

    /**
     * @param bool $isLastVisible
     * @param bool $isLast
     * @param int  $currentPage
     * @param int  $lastPage
     */
    protected function attachRight(bool $isLast, bool $isLastVisible, int $currentPage, int $lastPage)
    {
        if (!$isLastVisible) {
            $this->components[] = new Button('...', $this->fakeBtnAttr);
        }

        if (!$isLast) {
            $this->realBtnAttr[Button::ATTRIBUTE_HREF] = sprintf('%spage=%d', $this->baseUrl, $currentPage + 1);

            $this->components[] = new Button('>', $this->realBtnAttr, [], null, Button::TAG_A);
        }

        if (!$isLastVisible) {
            $this->realBtnAttr[Button::ATTRIBUTE_HREF] = sprintf('%spage=%d', $this->baseUrl, $lastPage);

            $this->components[] = new Button('>>', $this->realBtnAttr, [], null, Button::TAG_A);
        }
    }
}
