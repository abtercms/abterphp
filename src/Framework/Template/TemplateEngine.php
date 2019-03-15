<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Template;

class TemplateEngine
{
    const ERROR_MSG_CACHING_FAILURE = 'Caching failure';

    const ERROR_INVALID_LOADER = 'Loaders must be an instance of %s';

    const ERROR_INVALID_TEMPLATE_TYPE = 'Unexpected template type: %s';

    /** @var TemplateFactory */
    protected $templateFactory;

    /** @var ILoader[] */
    protected $loaders = [];

    /** @var string[] */
    protected $templateTypes = [];

    /** @var string[][] */
    protected $allSubTemplateIds = [];

    /** @var CacheManager */
    protected $cache;

    /**
     * PageLoader constructor.
     *
     * @param TemplateFactory $templateFactory
     * @param CacheManager    $cache
     */
    public function __construct(TemplateFactory $templateFactory, CacheManager $cache)
    {
        $this->templateFactory = $templateFactory;
        $this->cache           = $cache;
    }

    /**
     * @param string  $templateType
     * @param ILoader $loader
     */
    public function addLoader(string $templateType, ILoader $loader)
    {
        $this->templateTypes[] = $templateType;

        $this->loaders[$templateType] = $loader;
    }

    /**
     * Renders a list of templates
     * previously rendered templates can be referenced as variables
     * the last template rendered will be returned as a string
     *
     * @param string   $type
     * @param string   $documentId
     * @param string[] $templates
     * @param string[] $vars
     *
     * @return string
     */
    public function render(string $type, string $documentId, array $templates, array $vars): string
    {
        $cacheId = md5($type . '/' . $documentId);

        if ($this->hasValidCache($cacheId)) {
            return $this->cache->getDocument($cacheId);
        }

        $this->allSubTemplateIds = [];

        $content = '';
        foreach ($templates as $key => $template) {
            $content    = $this->renderTemplate($template, $vars);
            $vars[$key] = $content;
        }

        if (!$this->cache->storeSubTemplateCacheData($cacheId, $this->allSubTemplateIds)) {
            throw new TemplateException(static::ERROR_MSG_CACHING_FAILURE);
        }

        if (!$this->cache->storeDocument($cacheId, $content)) {
            throw new TemplateException(static::ERROR_MSG_CACHING_FAILURE);
        }

        return $content;
    }

    /**
     * @param string $cacheId
     *
     * @return bool
     */
    protected function hasValidCache(string $cacheId): bool
    {
        $cacheData = $this->cache->getSubTemplateCacheData($cacheId);
        if ($cacheData === null) {
            return false;
        }

        foreach ($cacheData->getSubTemplates() as $type => $identifiers) {
            if (!array_key_exists($type, $this->loaders)) {
                return false;
            }

            if ($this->loaders[$type]->hasAnyChangedSince($identifiers, $cacheData->getDate())) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $type
     * @param string $documentId
     *
     * @return SubTemplateCacheData|null
     */
    protected function getSubTemplateCacheData(string $type, string $documentId): ?SubTemplateCacheData
    {
        $cacheKey = sprintf('%s/%s', $type, $documentId);

        return $this->cache->getSubTemplateCacheData($cacheKey);
    }

    /**
     * @param string   $rawContent
     * @param string[] $vars
     *
     * @return string
     */
    protected function renderTemplate(string $rawContent, array $vars): string
    {
        $template = $this->templateFactory
            ->create($rawContent)
            ->setVars($vars)
            ->setTypes($this->templateTypes)
        ;

        $subTemplateIds = $template->parse();

        $this->addSubTemplateIds($subTemplateIds);

        $subTemplates = $this->loadSubTemplates($subTemplateIds);

        return $template->render($subTemplates);
    }

    /**
     * @param string[] $subTemplates
     *
     * @return string[]
     */
    protected function loadSubTemplates(array $subTemplates): array
    {
        if (count($subTemplates) === 0) {
            return [];
        }

        $result = [];
        foreach ($subTemplates as $type => $templateIdentifiers) {
            if (!array_key_exists($type, $this->loaders)) {
                $msg = sprintf(static::ERROR_INVALID_TEMPLATE_TYPE, $type);
                throw new \RuntimeException($msg);
            }

            $loader = $this->loaders[$type];

            /** @var TemplateData[] $entities */
            $entities = $loader->load($templateIdentifiers);

            foreach ($entities as $entity) {
                $vars    = $entity->getVars();
                $content = '';
                foreach ($entity->getTemplates() as $key => $template) {
                    $content    = $this->renderTemplate($template, $vars);
                    $vars[$key] = $content;
                }
                $result[$type][$entity->getIdentifier()] = $content;
            }
        }

        return $result;
    }

    /**
     * @param string[][] $subTemplateIds
     */
    protected function addSubTemplateIds(array $subTemplateIds)
    {
        foreach ($subTemplateIds as $type => $templateIdentifiers) {
            if (!array_key_exists($type, $this->allSubTemplateIds)) {
                $this->allSubTemplateIds[$type] = [];
            }

            $this->allSubTemplateIds[$type] = array_merge($this->allSubTemplateIds[$type], $templateIdentifiers);
        }
    }
}
