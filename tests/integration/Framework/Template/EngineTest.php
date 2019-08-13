<?php

namespace Integration\Framework\Template;

use AbterPhp\Framework\Template\CacheManager;
use AbterPhp\Framework\Template\Engine;
use AbterPhp\Framework\Template\Factory;
use AbterPhp\Framework\Template\Renderer;
use AbterPhp\Website\Databases\Queries\BlockCache;
use AbterPhp\Website\Domain\Entities\Block;
use AbterPhp\Website\Domain\Entities\Page;
use AbterPhp\Website\Orm\BlockRepo;
use AbterPhp\Website\Template\Loader\Block as BlockLoader;
use PHPUnit\Framework\MockObject\MockObject;

class EngineTest extends \PHPUnit\Framework\TestCase
{
    const CACHE_ALLOWED = true;
    const CACHE_NOT_ALLOWED = false;

    /** @var Engine */
    protected $sut;

    /** @var Factory */
    protected $templateFactory;

    /** @var Renderer */
    protected $renderer;

    /** @var BlockRepo|MockObject */
    protected $blockRepo;

    /** @var BlockCache|MockObject */
    protected $blockCache;

    /** @var CacheManager|MockObject */
    protected $cacheManager;

    public function setUp(): void
    {
        $this->templateFactory = new Factory();

        $this->renderer = new Renderer($this->templateFactory);

        $this->blockRepo = $this->getMockBuilder(BlockRepo::class)
            ->disableOriginalConstructor()
            ->setMethods(['getWithLayoutByIdentifiers'])
            ->getMock();

        $this->blockCache = $this->getMockBuilder(BlockCache::class)
            ->disableOriginalConstructor()
            ->setMethods(['hasAnyChangedSince'])
            ->getMock();

        $this->cacheManager = $this->getMockBuilder(CacheManager::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'getCacheData',
                    'storeCacheData',
                    'getDocument',
                    'storeDocument',
                ]
            )
            ->getMock();

        $this->cacheManager->expects($this->any())->method('storeCacheData')->willReturn(true);
        $this->cacheManager->expects($this->any())->method('storeDocument')->willReturn(true);

        $blockLoader = new BlockLoader($this->blockRepo, $this->blockCache);

        $this->sut = new Engine($this->renderer, $this->cacheManager, static::CACHE_NOT_ALLOWED);

        $this->renderer->addLoader('block', $blockLoader);
    }

    public function testRenderNoBlocks()
    {
        $expectedResult = 'abcde-f';

        $page = new Page(0, 'abc', 'f', 'abcd', 'abcde', false, null, '{{var/body}}-{{var/title}}');

        $actualResult = $this->sut->run(
            'page',
            $page->getIdentifier(),
            ['body' => $page->getBody(), 'layout' => $page->getLayout()],
            ['title' => $page->getTitle()]
        );

        $this->assertSame($expectedResult, $actualResult);
    }

    public function testRenderFlatBlocks()
    {
        $expectedResult = 'efg-h abcde-f ijk-l';

        $page = new Page(0, '', 'f', 'abcd', 'abcde', false, null, '{{block/header}} {{var/body}}-{{var/title}} {{block/footer}}');

        $block1 = new Block(0, 'header', 'h', 'efg', '{{var/body}}-{{var/title}}', null);
        $block2 = new Block(0, 'footer', 'l', 'ijk', '{{var/body}}-{{var/title}}', null);
        $this->blockRepo
            ->expects($this->any())
            ->method('getWithLayoutByIdentifiers')
            ->willReturn([$block1, $block2]);

        $actualResult = $this->sut->run(
            'page',
            $page->getIdentifier(),
            ['body' => $page->getBody(), 'layout' => $page->getLayout()],
            ['title' => $page->getTitle()]
        );

        $this->assertSame($expectedResult, $actualResult);
    }

    public function testRenderComplex()
    {
        $expectedResult = 'lmn-o efg-h pqr-s abcde-f xyz-0 tuv-w ijk-l';

        $page = new Page(0, '', 'f', 'abcd', 'abcde', false, null, '{{block/header}} {{var/body}}-{{var/title}} {{block/footer}}');

        $headerLayout = '{{block/header-sub-1}} {{var/body}}-{{var/title}} {{block/header-sub-2}}';
        $headerBlock  = new Block(0, 'header', 'h', 'efg', $headerLayout);
        $footerLayout = '{{block/footer-sub-1}} {{var/body}}-{{var/title}}';
        $footerBlock  = new Block(0, 'footer', 'l', 'ijk', $footerLayout);
        $this->blockRepo
            ->expects($this->at(0))
            ->method('getWithLayoutByIdentifiers')
            ->with(['header', 'footer'])
            ->willReturn([$headerBlock, $footerBlock]);

        $headerSub1 = new Block(0, 'header-sub-1', 'o', 'lmn', '{{var/body}}-{{var/title}}');
        $headerSub2 = new Block(0, 'header-sub-2', 's', 'pqr', '{{var/body}}-{{var/title}}');
        $this->blockRepo
            ->expects($this->at(1))
            ->method('getWithLayoutByIdentifiers')
            ->with(['header-sub-1', 'header-sub-2'])
            ->willReturn([$headerSub1, $headerSub2]);

        $footerSubLayout = '{{block/footer-sub-sub-1}} {{var/body}}-{{var/title}}';
        $footerSub1      = new Block(0, 'footer-sub-1', 'w', 'tuv', $footerSubLayout);
        $this->blockRepo
            ->expects($this->at(2))
            ->method('getWithLayoutByIdentifiers')
            ->with(['footer-sub-1'])
            ->willReturn([$footerSub1]);

        $footerSubSubLayout = '{{var/body}}-{{var/title}}';
        $footerSubSub1      = new Block(0, 'footer-sub-sub-1', '0', 'xyz', $footerSubSubLayout);
        $this->blockRepo
            ->expects($this->at(3))
            ->method('getWithLayoutByIdentifiers')
            ->with(['footer-sub-sub-1'])
            ->willReturn([$footerSubSub1]);

        $actualResult = $this->sut->run(
            'page',
            $page->getIdentifier(),
            ['body' => $page->getBody(), 'headerLayout' => $page->getLayout()],
            ['title' => $page->getTitle()]
        );

        $this->assertSame($expectedResult, $actualResult);
    }
}
