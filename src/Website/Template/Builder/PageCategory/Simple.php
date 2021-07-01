<?php

declare(strict_types=1);

namespace AbterPhp\Website\Template\Builder\PageCategory;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Html\Tag;
use AbterPhp\Framework\Template\Data;
use AbterPhp\Framework\Template\IBuilder;
use AbterPhp\Framework\Template\IData;
use AbterPhp\Framework\Template\ParsedTemplate;
use AbterPhp\Website\Constant\Event;
use AbterPhp\Website\Constant\Route;
use AbterPhp\Website\Domain\Entities\Page as Entity;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Routing\Urls\UrlException;
use Opulence\Routing\Urls\UrlGenerator;

class Simple implements IBuilder
{
    public const IDENTIFIER = 'simple';

    protected IEventDispatcher $dispatcher;

    protected UrlGenerator $urlGenerator;

    /**
     * PageCategory constructor.
     *
     * @param IEventDispatcher $dispatcher
     * @param UrlGenerator     $urlGenerator
     */
    public function __construct(IEventDispatcher $dispatcher, UrlGenerator $urlGenerator)
    {
        $this->dispatcher   = $dispatcher;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return static::IDENTIFIER;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param mixed               $data
     * @param ParsedTemplate|null $template
     *
     * @return IData
     * @throws UrlException
     */
    public function build($data, ?ParsedTemplate $template = null): IData
    {
        if (count($data) === 0) {
            throw new \InvalidArgumentException();
        }

        if (!$data[0]->getCategory()) {
            throw new \LogicException();
        }

        $category = $data[0]->getCategory();

        $body = $this->getCategoryHtml($data, $category->getName(), $category->getIdentifier());

        $this->dispatcher->dispatch(Event::PAGE_CATEGORY_READY, $body);

        return new Data(
            $category->getIdentifier(),
            [],
            ['body' => (string)$body]
        );
    }

    /**
     * @param Entity[] $pages
     * @param string   $categoryName
     * @param string   $categoryIdentifier
     *
     * @return Tag
     * @throws URLException
     */
    protected function getCategoryHtml(array $pages, string $categoryName, string $categoryIdentifier): Tag
    {
        $container = new Tag(null, [], [Html5::ATTR_CLASS => 'page-category'], Html5::TAG_DIV);

        $list = new Tag(null, [], [], Html5::TAG_UL);
        foreach ($pages as $page) {
            // @phan-suppress-next-line PhanTypeMismatchArgument
            $url = $this->urlGenerator->createFromName(Route::FALLBACK, $page->getIdentifier());
            $a   = new Tag($page->getTitle(), [], [Html5::ATTR_HREF => $url], Html5::TAG_A);

            $list[] = new Tag($a, [], [], Html5::TAG_LI);
        }

        // @phan-suppress-next-line PhanTypeMismatchArgument
        $url = $this->urlGenerator->createFromName(Route::FALLBACK, $categoryIdentifier);
        $a   = new Tag($categoryName, [], [Html5::ATTR_HREF => $url], Html5::TAG_A);

        $container[] = new Tag($a, [], [], Html5::TAG_H2);
        $container[] = $list;

        return $container;
    }
}
