<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Cell;

use AbterPhp\Framework\Html\Helper\StringHelper;
use AbterPhp\Framework\I18n\ITranslator;

class Sortable extends Cell implements ICell
{
    const NAME_PREFIX = 'sort-';

    const DIR_ASC = 'ASC';
    const DIR_DESC = 'DESC';

    /** @var string */
    protected $baseUrl;

    /** @var string */
    protected $fieldName = '';

    /** @var string */
    protected $inputName = '';

    /** @var array */
    protected $sortConditions = [];

    /** @var int */
    protected $value = 0;

    /**
     * Sortable constructor.
     *
     * @param string           $content
     * @param string           $group
     * @param string           $inputName
     * @param string           $fieldName
     * @param array            $attributes
     * @param ITranslator|null $translator
     */
    public function __construct(
        string $content,
        string $group,
        string $inputName,
        string $fieldName,
        array $attributes = [],
        ?ITranslator $translator = null
    ) {
        parent::__construct($content, $group, $attributes, $translator, self::HEAD);

        $this->fieldName = $fieldName;
        $this->inputName = static::NAME_PREFIX . $inputName;
    }

    /**
     * @param string $baseUrl
     *
     * @return $this
     */
    public function setBaseUrl(string $baseUrl): Sortable
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getQueryParam(): ?string
    {
        if ($this->value === 0) {
            return null;
        }

        return sprintf('%s=%s&', $this->inputName, $this->value);
    }

    /**
     * @param array $params
     *
     * @return $this
     */
    public function setParams(array $params): Sortable
    {
        if (empty($params[$this->inputName])) {
            return $this;
        }

        $this->value = (int)$params[$this->inputName];

        if ($this->value === 0) {
            return $this;
        }

        $dir = $this->value > 0 ? static::DIR_ASC : static::DIR_DESC;

        $this->sortConditions = [sprintf('%s %s', $this->fieldName, $dir)];

        return $this;
    }

    /**
     * @return array
     */
    public function getSortConditions(): array
    {
        return $this->sortConditions;
    }

    /**
     * @return string
     */
    public function getQueryPart(): string
    {
        if ($this->value === 0) {
            return '';
        }

        return sprintf('%s=%s', $this->inputName, $this->value);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $content = $this->content;

        if ($this->translator) {
            $content = $this->translator->translate($this->content);

            if (substr($content, 0, 2) === '{{') {
                $content = $this->content;
            }
        }

        $content .= $this->getDirection();

        return StringHelper::wrapInTag($content, $this->tag, $this->attributes);
    }

    /**
     * @return string
     */
    protected function getDirection(): string
    {
        if ($this->value === 0) {
            $class = 'caret-down';
            $dir   = '1';
        } elseif ($this->value > 0) {
            $class = 'caret-down caret-active';
            $dir   = '-1';
        } else {
            $class = 'caret-up caret-active';
            $dir   = '0';
        }

        return sprintf(
            '<a class="%s shoarting" href="%s%s=%s"></a>',
            $class,
            $this->baseUrl,
            $this->inputName,
            $dir
        );
    }
}
