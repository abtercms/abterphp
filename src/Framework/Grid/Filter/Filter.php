<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Filter;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\Html\Component;
use AbterPhp\Framework\Html\IComponent;
use AbterPhp\Framework\Html\INode;
use AbterPhp\Framework\Html\ITemplater;

abstract class Filter extends Component implements IFilter, ITemplater
{
    const DEFAULT_TAG = Html5::TAG_INPUT;

    /**
     * %1$s - input
     * %2$s - help
     */
    const DEFAULT_TEMPLATE = '%1$s %2$s';

    const NAME_PREFIX = 'filter-';

    const HELP_CONTENT = 'framework:helpPrefix';

    const QUERY_TEMPLATE = '%s = ?';

    const INTENT_HELP_BLOCK = 'help-block';

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

    /** @var string */
    protected $template = self::DEFAULT_TEMPLATE;

    /** @var IComponent */
    protected $wrapper;

    /** @var Label */
    protected $label;

    /** @var IComponent */
    protected $helpBlock;

    /**
     * Input constructor.
     *
     * @param string      $inputName
     * @param string      $fieldName
     * @param string[]    $intents
     * @param array       $attributes
     * @param string|null $tag
     */
    public function __construct(
        string $inputName = '',
        string $fieldName = '',
        array $intents = [],
        array $attributes = [],
        ?string $tag = null
    ) {
        $this->fieldName = $fieldName;
        $this->inputName = static::NAME_PREFIX . $inputName;

        $attributes[Html5::ATTR_ID]    = $this->inputName;
        $attributes[Html5::ATTR_NAME]  = $this->inputName;
        $attributes[Html5::ATTR_TITLE] = '';
        $attributes[Html5::ATTR_VALUE] = '';

        parent::__construct(null, $intents, $attributes, $tag);

        $this->wrapper = new Component(null, [], [], Html5::TAG_DIV);

        $this->label = new Label($this->inputName);

        $this->helpBlock = new Component(static::HELP_CONTENT, [static::INTENT_HELP_BLOCK], [], Html5::TAG_P);
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

        $this->attributes[Html5::ATTR_VALUE] = $this->value;

        $this->conditions = [sprintf(static::QUERY_TEMPLATE, $this->fieldName)];

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
     * @return IComponent
     */
    public function getWrapper(): IComponent
    {
        return $this->wrapper;
    }

    /**
     * @return Label|null
     */
    public function getLabel(): ?Label
    {
        return $this->label;
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
     * @return array
     */
    public function getAllNodes(int $depth = -1): array
    {
        $nodes = [$this->wrapper, $this->label, $this->helpBlock];

        if ($depth !== 0) {
            $nodes = array_merge(
                $nodes,
                $this->wrapper->getAllNodes($depth - 1),
                $this->label->getAllNodes($depth - 1),
                $this->helpBlock->getAllNodes($depth - 1)
            );
        }
        return array_merge($nodes, parent::getAllNodes($depth));
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $inputContent = parent::__toString();
        if ($this->label) {
            $inputContent = $this->wrapInputContent($inputContent);
        }

        $help = (string)$this->helpBlock;

        $content = sprintf($this->template, $inputContent, $help);

        $this->wrapper->setContent($content);

        return (string)$this->wrapper;
    }

    /**
     * @param string $inputContent
     *
     * @return string
     */
    protected function wrapInputContent(string $inputContent): string
    {
        $this->label->setContent($inputContent);

        return (string)$this->label;
    }
}
