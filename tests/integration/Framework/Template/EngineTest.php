<?php

namespace Integration\Framework\Template;

use AbterPhp\Framework\Template\CacheManager;
use AbterPhp\Framework\Template\TemplateEngine;
use AbterPhp\Framework\Template\TemplateFactory;
use AbterPhp\Website\Databases\Queries\BlockCache;
use AbterPhp\Website\Domain\Entities\Block;
use AbterPhp\Website\Domain\Entities\Page;
use AbterPhp\Website\Orm\BlockRepo;
use AbterPhp\Website\Template\BlockLoader;
use PHPUnit\Framework\MockObject\MockObject;

class EngineTest extends \PHPUnit\Framework\TestCase
{
    /** @var TemplateEngine */
    protected $sut;

    /** @var TemplateFactory */
    protected $templateFactory;

    /** @var BlockRepo|MockObject */
    protected $blockRepo;

    /** @var BlockCache|MockObject */
    protected $blockCache;

    /** @var CacheManager|MockObject */
    protected $cacheManager;

    public function setUp()
    {
        $this->templateFactory = new TemplateFactory();

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
                    'getSubTemplateCacheData',
                    'storeSubTemplateCacheData',
                    'getDocument',
                    'storeDocument',
                ]
            )
            ->getMock();

        $this->cacheManager->expects($this->any())->method('storeSubTemplateCacheData')->willReturn(true);
        $this->cacheManager->expects($this->any())->method('storeDocument')->willReturn(true);

        $blockLoader = new BlockLoader($this->blockRepo, $this->blockCache);

        $this->sut = new TemplateEngine($this->templateFactory, $this->cacheManager);

        $this->sut->addLoader('block', $blockLoader);
    }

    public function testRenderNoBlocks()
    {
        $expectedResult = 'abc-d';

        $page = new Page(0, 'abc', 'd', 'abc', '{{var/body}}-{{var/title}}');

        $actualResult = $this->sut->render(
            'page',
            $page->getIdentifier(),
            ['body' => $page->getBody(), 'layout' => $page->getLayout()],
            ['title' => $page->getTitle()]
        );

        $this->assertSame($expectedResult, $actualResult);
    }

    public function testRenderFlatBlocks()
    {
        $expectedResult = 'efg-h abc-d ijk-l';

        $page = new Page(0, '', 'd', 'abc', '{{block/header}} {{var/body}}-{{var/title}} {{block/footer}}');

        $block1 = new Block(0, 'header', 'h', 'efg', '{{var/body}}-{{var/title}}', null);
        $block2 = new Block(0, 'footer', 'l', 'ijk', '{{var/body}}-{{var/title}}', null);
        $this->blockRepo
            ->expects($this->once())
            ->method('getWithLayoutByIdentifiers')
            ->with(['footer', 'header'])
            ->willReturn([$block1, $block2]);

        $actualResult = $this->sut->render(
            'page',
            $page->getIdentifier(),
            ['body' => $page->getBody(), 'layout' => $page->getLayout()],
            ['title' => $page->getTitle()]
        );

        $this->assertSame($expectedResult, $actualResult);
    }

    public function testRenderComplex()
    {
        $expectedResult = 'lmn-o efg-h pqr-s abc-d xyz-0 tuv-w ijk-l';

        $page = new Page(0, '', 'd', 'abc', '{{block/header}} {{var/body}}-{{var/title}} {{block/footer}}');

        $headerLayout = '{{block/header-sub-1}} {{var/body}}-{{var/title}} {{block/header-sub-2}}';
        $headerBlock  = new Block(0, 'header', 'h', 'efg', $headerLayout);
        $footerLayout = '{{block/footer-sub-1}} {{var/body}}-{{var/title}}';
        $footerBlock  = new Block(0, 'footer', 'l', 'ijk', $footerLayout);
        $this->blockRepo
            ->expects($this->at(0))
            ->method('getWithLayoutByIdentifiers')
            ->with(['footer', 'header'])
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

        $actualResult = $this->sut->render(
            'page',
            $page->getIdentifier(),
            ['body' => $page->getBody(), 'headerLayout' => $page->getLayout()],
            ['title' => $page->getTitle()]
        );

        $this->assertSame($expectedResult, $actualResult);
    }
}
