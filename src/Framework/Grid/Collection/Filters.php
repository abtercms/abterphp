<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Collection;

use AbterPhp\Framework\Grid\Action\Button;
use AbterPhp\Framework\Grid\Filter\IFilter;
use AbterPhp\Framework\Html\Collection\Collection;
use AbterPhp\Framework\Html\Helper\StringHelper;
use AbterPhp\Framework\I18n\ITranslator;
use InvalidArgumentException;
use LogicException;

class Filters extends Collection
{
    const TAG_DIV  = 'div';
    const TAG_FORM = 'form';

    const ATTRIBUTE_TYPE = 'type';

    const ATTRIBUTES_FORM = [
        self::ATTRIBUTE_CLASS => 'filter-form',
    ];

    const ATTRIBUTES_SEARCH = [
        self::ATTRIBUTE_CLASS => 'btn btn-primary',
        self::ATTRIBUTE_TYPE  => 'submit',
    ];

    const ATTRIBUTES_RESET = [
        self::ATTRIBUTE_CLASS => 'btn filter-reset',
        self::ATTRIBUTE_TYPE  => 'submit',
    ];

    const COMPONENT_TEMPLATE = <<<'EOT'
<div class="hidable">
    <p class="hider"><button class="btn btn-info" type="button">%1$s</button></p>
    <div class="hidee">%2$s</div>
</div>
EOT;

    /** @var IFilter[] */
    protected $components = [];

    /** @var ITranslator */
    protected $translator;

    /**
     * Block constructor.
     *
     * @param string|null      $tag
     * @param array            $attributes
     * @param ITranslator|null $translator
     */
    public function __construct(?string $tag = null, array $attributes = [], ITranslator $translator = null)
    {
        parent::__construct($tag, $attributes);

        $this->translator = $translator;

        $this->components[] = new Button(
            $this->translator->translate('framework:filter'),
            Button::TAG_BUTTON,
            static::ATTRIBUTES_SEARCH
        );

        $this->components[] = new Button(
            $this->translator->translate('framework:reset'),
            Button::TAG_BUTTON,
            static::ATTRIBUTES_RESET
        );
    }

    /**
     * @return IFilter
     * @throws LogicException
     */
    public function current()
    {
        /** @var IFilter $object */
        $object = parent::current();

        $object = $this->verifyReturn($object, IFilter::class);

        return $object;
    }

    /**
     * @param int|null $offset
     * @param IFilter  $value
     *
     * @throws InvalidArgumentException
     */
    public function offsetSet($offset, $value)
    {
        $this->verifyArgument($value, IFilter::class);

        parent::offsetSet($offset, $value);
    }

    /**
     * @param int $offset
     *
     * @return IFilter|null
     * @throws LogicException
     */
    public function offsetGet($offset)
    {
        /** @var IFilter $object */
        $object = parent::offsetGet($offset);

        $this->verifyReturn($object, IFilter::class);

        return $object;
    }

    /**
     * @param array $params
     *
     * @return $this
     */
    public function setParams(array $params): Filters
    {
        foreach ($this->components as $component) {
            if (!($component instanceof IFilter)) {
                continue;
            }

            $component->setParams($params);
        }

        return $this;
    }

    /**
     * @param string $baseUrl
     *
     * @return string
     */
    public function getUrl(string $baseUrl): string
    {
        $queryParts = [];
        foreach ($this->components as $component) {
            if (!($component instanceof IFilter)) {
                continue;
            }

            $queryPart = $component->getQueryPart();
            if (!$queryPart) {
                continue;
            }

            $queryParts[] = $queryPart;
        }

        if (empty($queryParts)) {
            return $baseUrl;
        }

        return sprintf('%s%s&', $baseUrl, implode('&', $queryParts));
    }

    /**
     * @return array
     */
    public function getWhereConditions(): array
    {
        $conditions = [];
        foreach ($this->components as $component) {
            if (!($component instanceof IFilter)) {
                continue;
            }

            $conditions = array_merge($conditions, $component->getWhereConditions());
        }

        return $conditions;
    }

    /**
     * @return array
     */
    public function getSqlParams(): array
    {
        $params = [];
        foreach ($this->components as $component) {
            if (!($component instanceof IFilter)) {
                continue;
            }

            $params = array_merge($params, $component->getQueryParams());
        }

        return $params;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        if (count($this->components) <= 2) {
            return '';
        }

        $components = [];
        foreach ($this->components as $filter) {
            $components[] = (string)$filter;
        }

        $form = StringHelper::wrapInTag(
            implode("\n", $components),
            static::TAG_FORM,
            static::ATTRIBUTES_FORM
        );

        return sprintf(
            static::COMPONENT_TEMPLATE,
            $this->translator->translate('framework:filters'),
            $form
        );
    }
}
