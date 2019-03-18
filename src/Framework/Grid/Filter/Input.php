<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Filter;

use AbterPhp\Framework\Html\Component\Tag;
use AbterPhp\Framework\Html\Helper\StringHelper;
use AbterPhp\Framework\I18n\ITranslator;

class Input extends Tag implements IFilter
{
    const DEFAULT_TAG = self::TAG_INPUT;

    const TAG_DIV   = 'div';
    const TAG_LABEL = 'label';
    const TAG_INPUT = 'input';

    const ATTRIBUTE_NAME           = 'name';
    const ATTRIBUTE_ID             = 'id';
    const ATTRIBUTE_FOR            = 'for';
    const ATTRIBUTE_VALUE          = 'value';
    const ATTRIBUTE_DATA_TOGGLE    = 'data-toggle';
    const ATTRIBUTE_DATA_PLACEMENT = 'data-placement';
    const ATTRIBUTE_TITLE          = 'title';

    const DATA_TOGGLE_TOOLTIP = 'tooltip';

    const DATA_PLACEMENT_LEFT = 'left';

    const NAME_PREFIX = 'filter-';

    const COMPONENT_TEMPLATE = <<<'EOT'
<div class="form-group pmd-textfield pmd-textfield-floating-label">
   <label for="%1$s" class="control-label">%2$s</label>
    %3$s
</div>
EOT;

    const INPUT_CLASS = 'form-control';

    const FILTER_EXACT  = '%s = ?';
    const FILTER_PREFIX = "%s LIKE ?";
    const FILTER_LIKE   = "%s LIKE ? ";
    const FILTER_REGEXP = "%s REGEXP ?";

    /** @var array */
    protected $filterTypes = [self::FILTER_EXACT, self::FILTER_PREFIX, self::FILTER_LIKE];

    /** @var string */
    protected $filterType = '';

    /** @var string */
    protected $fieldName = '';

    /** @var string */
    protected $inputName = '';

    /** @var array */
    protected $conditions = [];

    /** @var array */
    protected $queryParams = [];

    /** @var string */
    protected $value = '';

    /**
     * Input constructor.
     *
     * @param string           $inputName
     * @param string           $fieldName
     * @param string           $content
     * @param string           $filterType
     * @param array            $attributes
     * @param ITranslator|null $translator
     * @param string|null      $tag
     */
    public function __construct(
        string $inputName = '',
        string $fieldName = '',
        string $content = '',
        string $filterType = self::FILTER_EXACT,
        $attributes = [],
        ?ITranslator $translator = null,
        ?string $tag = null
    ) {
        if (!in_array($filterType, $this->filterTypes)) {
            throw new \InvalidArgumentException('Invalid filter type.');
        }

        $this->fieldName  = $fieldName;
        $this->inputName  = static::NAME_PREFIX . $inputName;
        $this->filterType = $filterType;

        $attributes[static::ATTRIBUTE_ID]             = $this->inputName;
        $attributes[static::ATTRIBUTE_NAME]           = $this->inputName;
        $attributes[static::ATTRIBUTE_CLASS]          = static::INPUT_CLASS;
        $attributes[static::ATTRIBUTE_DATA_TOGGLE]    = static::DATA_TOGGLE_TOOLTIP;
        $attributes[static::ATTRIBUTE_DATA_PLACEMENT] = static::DATA_PLACEMENT_LEFT;
        $attributes[static::ATTRIBUTE_TITLE]          = '';
        $attributes[static::ATTRIBUTE_VALUE]          = '';

        parent::__construct($content, $attributes, $translator, $tag);
    }

    /**
     * @param array $params
     *
     * @return $this
     */
    public function setParams(array $params): IFilter
    {
        if (empty($params[$this->inputName])) {
            return $this;
        }

        $this->value = $params[$this->inputName];

        $this->attributes[static::ATTRIBUTE_VALUE] = $this->value;

        $this->conditions = [sprintf($this->filterType, $this->fieldName)];

        $this->queryParams = [];
        switch ($this->filterType) {
            case static::FILTER_EXACT:
                $this->queryParams[] = $this->value;
                break;
            case static::FILTER_PREFIX:
                $this->queryParams[] = sprintf('%s%%', $this->value);
                break;
            case static::FILTER_LIKE:
                $value = implode('%', explode(' ', $this->value));

                $this->queryParams[] = sprintf('%%%s%%', $value);
                break;
            case static::FILTER_REGEXP:
                $this->queryParams[] = $this->value;
                break;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getWhereConditions(): array
    {
        return $this->conditions;
    }

    /**
     * @return array
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    /**
     * @return string
     */
    public function getQueryPart(): string
    {
        if (empty($this->value)) {
            return '';
        }

        return sprintf('%s=%s', $this->inputName, urlencode($this->value));
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $this->attributes[static::ATTRIBUTE_TITLE] = $this->getHelp();

        $inputTag     = StringHelper::createTag(static::TAG_INPUT, $this->attributes);
        $labelContent = $this->translator->translate($this->content);

        return sprintf(static::COMPONENT_TEMPLATE, $this->inputName, $labelContent, $inputTag);
    }

    /**
     * @return string
     */
    protected function getHelp()
    {
        switch ($this->filterType) {
            case static::FILTER_EXACT:
                return $this->translator->translate('framework:helpExact');
            case static::FILTER_PREFIX:
                return $this->translator->translate('framework:helpPrefix');
            case static::FILTER_REGEXP:
                return $this->translator->translate('framework:helpRegexp');
            case static::FILTER_LIKE:
                return $this->translator->translate('framework:helpLike');
        }

        return '';
    }
}
