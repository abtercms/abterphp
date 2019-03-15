<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Template;

class Template
{
    /** @var string */
    protected $rawContent = '';

    /** @var []string */
    protected $subTemplates = [];

    /** @var []string */
    protected $vars = [];

    /** @var array */
    protected $types = ['block'];

    /**
     * Template constructor.
     *
     * @param string   $rawContent
     * @param string[] $vars
     * @param string[] $types
     */
    public function __construct(string $rawContent = '', array $vars = [], array $types = ['block'])
    {
        $this->rawContent = $rawContent;
        $this->vars       = $vars;
        $this->types      = $types;
    }

    /**
     * @param string $rawContent
     *
     * @return Template
     */
    public function setRawContent(string $rawContent): Template
    {
        $this->rawContent = $rawContent;

        return $this;
    }

    /**
     * @param string[] $vars
     *
     * @return Template
     */
    public function setVars(array $vars): Template
    {
        $this->vars = $vars;

        return $this;
    }

    /**
     * @param string[] $types
     *
     * @return Template
     */
    public function setTypes(array $types): Template
    {
        $this->types = $types;

        return $this;
    }

    /**
     * @return array
     */
    public function getSubTemplates(): array
    {
        return array_keys($this->subTemplates);
    }

    /**
     * @return string[][]
     */
    public function parse(): array
    {
        if (!$this->rawContent) {
            return [];
        }

        if (!$this->replaceVars()) {
            return [];
        }

        $this->subTemplates = $this->parseTemplates();

        $this->filterUniqueMatches();

        return $this->parseResult();
    }

    /**
     * @return bool
     */
    private function replaceVars(): bool
    {
        $matches = [];
        $result  = preg_match_all('/\{\{\s*var\/([\w-]+)\s*\}\}/', $this->rawContent, $matches);
        if ($result === false) {
            return false;
        }

        if (count($matches) < 2) {
            return count($matches) == 0;
        }

        foreach ($matches[1] as $idx => $varName) {
            if (!array_key_exists($idx, $matches[0])) {
                return false;
            }

            $search  = $matches[0][$idx];
            $replace = '';
            if (array_key_exists($varName, $this->vars)) {
                $replace = $this->vars[$varName];
            }

            $this->rawContent = str_replace($search, $replace, $this->rawContent);
        }

        return true;
    }

    /**
     * @return string[][][]
     */
    private function parseTemplates(): array
    {
        $matches = [];
        $pattern = sprintf('/\{\{\s*(%s)\/([\w-]+)\s*\}\}/', implode('|', $this->types));
        $result  = preg_match_all($pattern, $this->rawContent, $matches);
        if ($result === false) {
            return [];
        }

        $subTemplates = [];
        foreach ($matches[0] as $idx => $occurrence) {
            if (!isset($matches[1][$idx]) || !isset($matches[2][$idx])) {
                return [];
            }

            $type       = $matches[1][$idx];
            $templateId = $matches[2][$idx];

            if (!isset($this->subTemplates[$templateId])) {
                $subTemplates[$type][$templateId] = [$occurrence];

                continue;
            }

            $subTemplates[$type][$templateId][] = $occurrence;
        }

        return $subTemplates;
    }

    /**
     * @return bool
     */
    protected function filterUniqueMatches(): bool
    {
        foreach ($this->subTemplates as $type => $typeTemplates) {
            foreach ($typeTemplates as $templateId => $instances) {
                if (count($instances) < 2) {
                    continue;
                }

                $this->subTemplates[$type][$templateId] = array_values(array_flip(array_flip($instances)));
            }

            ksort($this->subTemplates[$type]);
        }

        ksort($this->subTemplates);

        return true;
    }

    /**
     * @return string[]
     */
    protected function parseResult(): array
    {
        return array_map(
            function ($typeArray) {
                return array_keys($typeArray);
            },
            $this->subTemplates
        );
    }

    /**
     * @param string[][] $subTemplates
     *
     * @return string
     */
    public function render(array $subTemplates): string
    {
        $content = $this->rawContent;

        foreach ($this->subTemplates as $type => $typeTemplates) {
            if (!array_key_exists($type, $subTemplates)) {
                $subTemplates[$type] = [];
            }
            foreach ($typeTemplates as $templateId => $occurrences) {
                $replace = '';
                if (array_key_exists($templateId, $subTemplates[$type])) {
                    $replace = $subTemplates[$type][$templateId];
                }

                $content = str_replace($occurrences, $replace, $content);
            }
        }

        return $content;
    }
}
