<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Component;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Grid\Action\Action;
use AbterPhp\Framework\Grid\Filter\Filter;
use AbterPhp\Framework\Grid\Filter\IFilter;
use AbterPhp\Framework\Html\Helper\Attributes;
use AbterPhp\Framework\Html\Helper\Tag as TagHelper;
use AbterPhp\Framework\Html\INode;
use AbterPhp\Framework\Html\ITemplater;
use AbterPhp\Framework\Html\Node;
use AbterPhp\Framework\Html\Tag;

class Filters extends Tag implements ITemplater
{
    public const BTN_CONTENT_FILTERS = 'framework:filters';
    public const BTN_CONTENT_FILTER  = 'framework:filter';
    public const BTN_CONTENT_RESET   = 'framework:reset';

    protected const DEFAULT_TAG       = Html5::TAG_FORM;
    protected const CONTENT_TYPE      = Filter::class;
    protected const FILTER_FORM_CLASS = 'filter-form';

    protected const FORM_ATTRIBS   = [Html5::ATTR_CLASS => self::FILTER_FORM_CLASS];
    protected const SEARCH_ATTRIBS = [Html5::ATTR_TYPE => Action::TYPE_SUBMIT];
    protected const RESET_ATTRIBS  = [Html5::ATTR_TYPE => Action::TYPE_SUBMIT];
    /**
     * %1$s - hider button
     * %2$s - nodes (filters)
     */
    protected const DEFAULT_TEMPLATE = <<<'EOT'
        <div class="hideable">
            <p class="hider">%1$s</p>
            <div class="hidee">%2$s</div>
        </div>
        EOT;

    protected string $template = self::DEFAULT_TEMPLATE;

    /** @var IFilter[] */
    protected array $content = [];

    protected string $nodeClass = IFilter::class;

    protected Action $hiderBtn;
    protected Action $filterBtn;
    protected Action $resetBtn;

    /**
     * Filters constructor.
     *
     * @param string[]            $intents
     * @param array<string,mixed> $attributes
     * @param string|null         $tag
     */
    public function __construct(array $intents = [], array $attributes = [], ?string $tag = null)
    {
        parent::__construct(null, $intents, $attributes, $tag);

        $this->hiderBtn  = new Action(static::BTN_CONTENT_FILTERS, [Action::INTENT_INFO]);
        $this->filterBtn = new Action(static::BTN_CONTENT_FILTER, [Action::INTENT_PRIMARY], static::SEARCH_ATTRIBS);
        $this->resetBtn  = new Action(static::BTN_CONTENT_RESET, [Action::INTENT_SECONDARY], static::RESET_ATTRIBS);
    }

    /**
     * @param array $params
     *
     * @return $this
     */
    public function setParams(array $params): Filters
    {
        foreach ($this->content as $filter) {
            $filter->setParams($params);
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
        foreach ($this->content as $filter) {
            $queryPart = $filter->getQueryPart();
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
        foreach ($this->content as $filter) {
            $conditions = array_merge($conditions, $filter->getWhereConditions());
        }

        return $conditions;
    }

    /**
     * @return array<string,string>
     */
    public function getSqlParams(): array
    {
        $params = [];
        foreach ($this->content as $filter) {
            $params = array_merge($params, $filter->getQueryParams());
        }

        return $params;
    }

    /**
     * @return INode[]
     */
    public function getExtendedNodes(): array
    {
        return array_merge([$this->hiderBtn, $this->filterBtn, $this->resetBtn], $this->getNodes());
    }

    /**
     * @param string $template
     *
     * @return INode
     */
    public function setTemplate(string $template): INode
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $content = Node::__toString();

        $form = TagHelper::toString($this->tag, $content, Attributes::fromArray(static::FORM_ATTRIBS));

        return sprintf(
            $this->template,
            (string)$this->hiderBtn,
            $form
        );
    }

    /**
     * @return Action
     */
    public function getHiderBtn(): Action
    {
        return $this->hiderBtn;
    }

    /**
     * @return Action
     */
    public function getFilterBtn(): Action
    {
        return $this->filterBtn;
    }

    /**
     * @return Action
     */
    public function getResetBtn(): Action
    {
        return $this->resetBtn;
    }
}
