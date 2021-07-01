<?php

declare(strict_types=1);

namespace AbterPhp\PropellerAdmin\Tests\Decorator;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\Grid\Filter\ExactFilter;
use AbterPhp\Framework\Grid\Filter\Filter;
use AbterPhp\Framework\Grid\Filter\IFilter;
use AbterPhp\Framework\Grid\Filter\LikeFilter;
use AbterPhp\Framework\Grid\Pagination\IPagination;
use AbterPhp\Framework\Grid\Pagination\Pagination;
use AbterPhp\Framework\Html\Tag;
use AbterPhp\PropellerAdmin\Decorator\Grid;
use PHPUnit\Framework\TestCase;

class GridTest extends TestCase
{
    /** @var Grid - System Under Test */
    protected Grid $sut;

    public function setUp(): void
    {
        $this->sut = (new Grid())->init();

        parent::setUp();
    }

    public function testDecorateWithEmptyComponents()
    {
        $this->sut->decorate([]);

        $this->assertTrue(true, 'No error was found.');
    }

    public function testDecorateNonMatchingComponents()
    {
        $this->sut->decorate([new Tag()]);

        $this->assertTrue(true, 'No error was found.');
    }

    public function testDecorateWithDoubleInit()
    {
        $this->sut->init();

        $this->sut->decorate([new Tag()]);

        $this->testDecorateNonMatchingComponents();
    }

    public function testDecorateFilters()
    {
        /** @var IFilter[] $nodes */
        $nodes = [new ExactFilter(), new LikeFilter()];

        $this->sut->decorate($nodes);

        $this->assertStringContainsString(
            Grid::FILTER_INPUT_CLASS,
            $nodes[0]->getAttribute(Html5::ATTR_CLASS)->getValue()
        );
        $this->assertStringContainsString(
            Grid::FILTER_INPUT_CLASS,
            $nodes[1]->getAttribute(Html5::ATTR_CLASS)->getValue()
        );
    }

    public function testDecoratePaginations()
    {
        $paginationStub0 = $this->getMockBuilder(Pagination::class)
            ->disableOriginalConstructor()
            ->getMock();

        $paginationStub1 = $this->getMockBuilder(Pagination::class)
            ->disableOriginalConstructor()
            ->getMock();

        $paginationStub0
            ->expects($this->any())
            ->method('isMatch')
            ->willReturnOnConsecutiveCalls(false, true, false, false, false, false, false);

        $paginationStub1
            ->expects($this->any())
            ->method('isMatch')
            ->willReturnOnConsecutiveCalls(false, true, false, false, false, false, false);

        $paginationStub0->expects($this->atLeastOnce())->method('setTemplate')->with(Grid::PAGINATION_TEMPLATE);
        $paginationStub1->expects($this->atLeastOnce())->method('setTemplate')->with(Grid::PAGINATION_TEMPLATE);

        /** @var IPagination[] $nodes */
        $nodes = [$paginationStub0, $paginationStub1];

        $this->sut->decorate($nodes);
    }

    public function testDecorateHelpBlocks()
    {
        $input = new Input('a', 'A', '', [Filter::INTENT_HELP_BLOCK]);
        $label = new Label('a', 'A', [Filter::INTENT_HELP_BLOCK]);
        $component = new Tag('c', [Filter::INTENT_HELP_BLOCK]);

        $nodes = [$input, $label, $component];

        $this->sut->decorate($nodes);

        $this->assertStringContainsString(Grid::HELP_BLOCK_CLASS, $input->getAttribute(Html5::ATTR_CLASS)->getValue());
        $this->assertStringContainsString(Grid::HELP_BLOCK_CLASS, $label->getAttribute(Html5::ATTR_CLASS)->getValue());
        $this->assertStringContainsString(
            Grid::HELP_BLOCK_CLASS,
            $component->getAttribute(Html5::ATTR_CLASS)->getValue()
        );
    }
}
