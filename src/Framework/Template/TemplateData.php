<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Template;

class TemplateData implements ITemplateData
{
    /**
     * @var string
     * identifier of the template
     * given that the object is returned by template loader responsible for "block" templates
     * an identifier of "first-block" would mean that the template will be substituted in
     * other templates having the subtemplate {{block/first-block}}
     */
    protected $identifier = '';

    /**
     * @var string[]
     * variables which are possible to inject into templates, key is important
     * variable subtemplates in templates looks like this {{var/keyName}}
     * where keyName is the name of key in $vars
     */
    protected $vars = [];

    /**
     * @var string[]
     */
    protected $templates = [];

    /**
     * TemplateData constructor.
     *
     * @param string $identifier
     * @param array  $templates
     * @param array  $vars
     */
    public function __construct($identifier = '', $vars = [], $templates = [])
    {
        $this->identifier = $identifier;
        $this->vars       = $vars;
        $this->templates  = $templates;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     *
     * @return TemplateData
     */
    public function setIdentifier(string $identifier): TemplateData
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return array
     */
    public function getTemplates(): array
    {
        return $this->templates;
    }

    /**
     * @param array $templates
     *
     * @return TemplateData
     */
    public function setTemplates(array $templates): TemplateData
    {
        $this->templates = $templates;

        return $this;
    }

    /**
     * @return array
     */
    public function getVars(): array
    {
        return $this->vars;
    }

    /**
     * @param array $vars
     *
     * @return TemplateData
     */
    public function setVars(array $vars): TemplateData
    {
        $this->vars = $vars;

        return $this;
    }
}
