<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Template;

use Opulence\Cache\ICacheBridge;

class CacheManager
{
    const CACHE_KEY_TEMPLATES = 'templates_%s';
    const CACHE_KEY_DOCUMENT  = 'document_%s';

    const ASTERIX = '*';

    const FORMAT_JSON = 'json';

    /** @var ICacheBridge */
    protected $cacheBridge;

    /**
     * Cache constructor.
     *
     * @param ICacheBridge $cacheBridge
     */
    public function __construct(ICacheBridge $cacheBridge)
    {
        $this->cacheBridge = $cacheBridge;
    }

    /**
     * @param string $cacheId
     *
     * @return SubTemplateCacheData|null
     */
    public function getSubTemplateCacheData(string $cacheId): ?SubTemplateCacheData
    {
        $key     = $this->getSubTemplateCacheKey($cacheId);
        try {
            $payload = $this->cacheBridge->get($key);
        } catch (\Exception $e) {
            return null;
        }
        if (empty($payload) || !is_string($payload)) {
            return null;
        }

        return SubTemplateCacheData::fromPayload($payload);
    }

    /**
     * @param string $documentId
     * @param array  $blocks
     *
     * @return bool
     */
    public function storeSubTemplateCacheData(string $cacheId, array $blocks): bool
    {
        $cacheData = (new SubTemplateCacheData())->setSubTemplates($blocks);

        $payload = $cacheData->toPayload();

        $key = $this->getSubTemplateCacheKey($cacheId);

        $this->cacheBridge->set($key, $payload, PHP_INT_MAX);

        return $this->cacheBridge->has($key);
    }

    /**
     * @param string $cacheId
     *
     * @return string
     */
    public function getDocument(string $cacheId): string
    {
        $key = $this->getDocumentCacheKey($cacheId);

        try {
            return (string)$this->cacheBridge->get($key);
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * @param string $cacheId
     * @param string $payload
     *
     * @return bool
     */
    public function storeDocument(string $cacheId, string $payload): bool
    {
        $key = $this->getDocumentCacheKey($cacheId);

        $this->cacheBridge->set($key, $payload, PHP_INT_MAX);

        return $this->cacheBridge->has($key);
    }

    public function flush()
    {
        $this->cacheBridge->flush();
    }

    /**
     * @param string $cacheId
     *
     * @return string
     */
    private function getSubTemplateCacheKey(string $cacheId): string
    {
        return sprintf(static::CACHE_KEY_TEMPLATES, $cacheId);
    }

    /**
     * @param string $cacheId
     *
     * @return string
     */
    private function getDocumentCacheKey(string $cacheId): string
    {
        return sprintf(static::CACHE_KEY_DOCUMENT, $cacheId);
    }
}
