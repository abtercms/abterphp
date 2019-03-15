<?php

declare(strict_types=1);

namespace AbterPhp\Website\Template;

use AbterPhp\Framework\Template\ILoader;
use AbterPhp\Framework\Template\TemplateData;
use AbterPhp\Website\Databases\Queries\BlockCache;
use AbterPhp\Website\Orm\BlockRepo;

class BlockLoader implements ILoader
{
    /**
     * @var BlockRepo
     */
    protected $blockRepo;

    /**
     * @var BlockCache
     */
    protected $blockCache;

    /**
     * BlockLoader constructor.
     *
     * @param BlockRepo  $blockRepo
     * @param BlockCache $blockCache
     */
    public function __construct(BlockRepo $blockRepo, BlockCache $blockCache)
    {
        $this->blockRepo  = $blockRepo;
        $this->blockCache = $blockCache;
    }

    /**
     * @param string[] $identifiers
     *
     * @return TemplateData[]
     */
    public function load(array $identifiers): array
    {
        $blocks = $this->blockRepo->getWithLayoutByIdentifiers($identifiers);

        $templateData = [];
        foreach ($blocks as $block) {
            $templateData[] = new TemplateData(
                $block->getIdentifier(),
                ['title' => $block->getTitle()],
                ['body' => $block->getBody(), 'layout' => $block->getLayout()]
            );
        }

        return $templateData;
    }

    /**
     * @param string[] $identifiers
     * @param string   $cacheTime
     *
     * @return bool
     */
    public function hasAnyChangedSince(array $identifiers, string $cacheTime): bool
    {
        return $this->blockCache->hasAnyChangedSince($identifiers, $cacheTime);
    }
}
