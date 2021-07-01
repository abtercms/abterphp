<?php

declare(strict_types=1);

namespace AbterPhp\Website\Template\Builder\PageCategory;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Html\Tag;
use AbterPhp\Framework\I18n\ITranslator;
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

class Detailed implements IBuilder
{
    public const IDENTIFIER = 'detailed';

    protected const MORE_BTN_CONTAINER_CLASS = 'more-btn-container';

    protected const CLASS_LEDE = 'detailed-lede';

    protected IEventDispatcher $dispatcher;

    protected UrlGenerator $urlGenerator;

    protected ITranslator $translator;

    /**
     * PageCategory constructor.
     *
     * @param IEventDispatcher $dispatcher
     * @param UrlGenerator     $urlGenerator
     * @param ITranslator      $translator
     */
    public function __construct(IEventDispatcher $dispatcher, UrlGenerator $urlGenerator, ITranslator $translator)
    {
        $this->dispatcher   = $dispatcher;
        $this->urlGenerator = $urlGenerator;
        $this->translator   = $translator;
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

        $body = $this->buildCategory($data, $category->getName(), $category->getIdentifier());

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
    protected function buildCategory(array $pages, string $categoryName, string $categoryIdentifier): Tag
    {
        $container = new Tag(null, [], [Html5::ATTR_CLASS => 'page-category'], Html5::TAG_SECTION);

        $list = new Tag(null, [], [Html5::ATTR_CLASS => 'page-container'], Html5::TAG_DIV);
        foreach ($pages as $page) {
            $list[] = $this->buildPage($page);
        }

        // @phan-suppress-next-line PhanTypeMismatchArgument
        $url = $this->urlGenerator->createFromName(Route::FALLBACK, $categoryIdentifier);
        $a   = new Tag($categoryName, [], [Html5::ATTR_HREF => $url], Html5::TAG_A);

        $container[] = new Tag($a, [], [], Html5::TAG_H2);
        $container[] = $list;

        return $container;
    }

    /**
     * @param Entity $page
     *
     * @return Tag
     * @throws URLException
     */
    protected function buildPage(Entity $page): Tag
    {
        $item = new Tag(null, [], [], Html5::TAG_ARTICLE);

        // @phan-suppress-next-line PhanTypeMismatchArgument
        $url = $this->urlGenerator->createFromName(Route::FALLBACK, $page->getIdentifier());

        $item[] = $this->buildPageTitle($page, $url);
        $item[] = $this->buildPageLede($page);
        $item[] = $this->buildPageButtons($url);

        return $item;
    }

    /**
     * @param Entity $page
     * @param string $url
     *
     * @return Tag
     */
    protected function buildPageTitle(Entity $page, string $url): Tag
    {
        $a = new Tag($page->getTitle(), [], [Html5::ATTR_HREF => $url], Html5::TAG_A);

        return new Tag($a, [], [], Html5::TAG_H3);
    }

    /**
     * @param Entity $page
     *
     * @return Tag
     */
    protected function buildPageLede(Entity $page): Tag
    {
        $lede = new Tag(null, [], [Html5::ATTR_CLASS => static::CLASS_LEDE], Html5::TAG_DIV);
        foreach (explode("\n", $page->getLede()) as $paragraph) {
            if (trim($paragraph) === '') {
                continue;
            }

            $lede[] = new Tag($paragraph, [], [], Html5::TAG_P);
        }

        return $lede;
    }

    /**
     * @param string $url
     *
     * @return Tag
     */
    protected function buildPageButtons(string $url): Tag
    {
        $iconHtml = '<i class="fas fa-angle-right"></i>';
        $aContent = sprintf('%s&nbsp;%s', $this->translator->translate('website:more'), $iconHtml);

        $a = new Tag($aContent, [], [Html5::ATTR_HREF => $url], Html5::TAG_A);
        $p = new Tag($a, [], [], Html5::TAG_P);

        return new Tag($p, [], [Html5::ATTR_CLASS => static::MORE_BTN_CONTAINER_CLASS], Html5::TAG_DIV);
    }
}
