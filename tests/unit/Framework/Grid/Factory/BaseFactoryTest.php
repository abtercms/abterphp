<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Factory;

use AbterPhp\Framework\Grid\Component\Filters;
use AbterPhp\Framework\Grid\Grid;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\I18n\MockTranslatorFactory;
use Opulence\Routing\Urls\UrlGenerator;
use PHPUnit\Framework\MockObject\MockObject;

class BaseFactoryTest extends \PHPUnit\Framework\TestCase
{
    /** @var BaseFactory|MockObject */
    protected $sut;

    /** @var UrlGenerator|MockObject */
    protected $urlGenerator;

    /** @var PaginationFactory|MockObject */
    protected $paginationFactory;

    /** @var TableFactory|MockObject */
    protected $tableFactory;

    /** @var GridFactory|MockObject */
    protected $gridFactory;

    /** @var ITranslator|MockObject */
    protected $translator;

    /** @var Filters|MockObject */
    protected $filters;

    public function setUp()
    {
        $this->urlGenerator = $this->getMockBuilder(UrlGenerator::class)
            ->disableOriginalConstructor()
            ->setMethods(['createFromName'])
            ->getMock();

        $this->paginationFactory = $this->getMockBuilder(PaginationFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->tableFactory = $this->getMockBuilder(TableFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->gridFactory = $this->getMockBuilder(GridFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->translator = MockTranslatorFactory::createSimpleTranslator($this, []);

        $this->filters = $this->getMockBuilder(Filters::class)
            ->disableOriginalConstructor()
            ->setMethods(['setParams', 'getUrl'])
            ->getMock();


        $this->sut = $this->getMockForAbstractClass(
            BaseFactory::class,
            [
                $this->urlGenerator,
                $this->paginationFactory,
                $this->tableFactory,
                $this->gridFactory,
                $this->translator,
                $this->filters
            ]
        );
    }

    public function testCreateGrid()
    {
        $params  = ['foo' => 'Foo'];
        $baseUrl = '/foo?';

        $this->paginationFactory->expects($this->once())->method('create');
        $this->tableFactory->expects($this->once())->method('create');
        $this->gridFactory->expects($this->once())->method('create');
        $this->filters->expects($this->once())->method('setParams');
        $this->filters->expects($this->once())->method('getUrl');

        $grid = $this->sut->createGrid($params, $baseUrl);

        $this->assertInstanceOf(Grid::class, $grid);
    }
}
