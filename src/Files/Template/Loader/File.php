<?php

declare(strict_types=1);

namespace AbterPhp\Files\Template\Loader;

use AbterPhp\Files\Constant\Route;
use AbterPhp\Files\Domain\Entities\File as Entity;
use AbterPhp\Files\Orm\FileRepo;
use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Html\Helper\Attributes;
use AbterPhp\Framework\Html\Helper\Tag as TagHelper;
use AbterPhp\Framework\Template\Data;
use AbterPhp\Framework\Template\IData;
use AbterPhp\Framework\Template\ILoader;
use AbterPhp\Framework\Template\ParsedTemplate;
use Opulence\Orm\OrmException;
use Opulence\Routing\Urls\UrlException;
use Opulence\Routing\Urls\UrlGenerator;

class File implements ILoader
{
    protected const FILE_CATEGORY_CLASS = 'file-category';

    protected FileRepo $fileRepo;

    protected UrlGenerator $urlGenerator;

    /**
     * File constructor.
     *
     * @param FileRepo     $fileRepo
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(FileRepo $fileRepo, UrlGenerator $urlGenerator)
    {
        $this->fileRepo     = $fileRepo;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param ParsedTemplate[] $parsedTemplates
     *
     * @return IData[]
     * @throws OrmException|UrlException
     */
    public function load(array $parsedTemplates): array
    {
        $identifiers = array_keys($parsedTemplates);

        $files = $this->fileRepo->getPublicByCategoryIdentifiers($identifiers);

        $filesByCategories = $this->getFilesByCategory($files);

        return $this->getTemplateData($filesByCategories);
    }

    /**
     * @param Entity[] $files
     *
     * @return Entity[][]
     */
    protected function getFilesByCategory(array $files): array
    {
        $return = [];
        foreach ($files as $file) {
            $return[$file->getCategory()->getIdentifier()][] = $file;
        }

        return $return;
    }

    /**
     * @param Entity[][] $files
     *
     * @return IData[]
     * @throws UrlException
     */
    protected function getTemplateData(array $files): array
    {
        $templateData = [];
        foreach ($files as $categoryIdentifier => $categoryFiles) {
            $templateData[] = new Data(
                $categoryIdentifier,
                [],
                ['body' => $this->getCategoryHtml($categoryFiles, $categoryIdentifier)]
            );
        }

        return $templateData;
    }

    /**
     * @param Entity[] $files
     * @param string   $categoryIdentifier
     *
     * @return string
     * @throws URLException
     */
    protected function getCategoryHtml(array $files, string $categoryIdentifier): string
    {
        $html = [];
        foreach ($files as $file) {
            // @phan-suppress-next-line PhanTypeMismatchArgument
            $url  = $this->urlGenerator->createFromName(Route::PUBLIC_FILE, $file->getFilesystemName());
            $link = TagHelper::toString(
                Html5::TAG_A,
                $file->getPublicName(),
                Attributes::fromArray(
                    [
                        Html5::ATTR_HREF => $url,
                    ]
                )
            );

            $html[] = TagHelper::toString(Html5::TAG_LI, $link);
        }

        return TagHelper::toString(
            Html5::TAG_UL,
            implode('', $html),
            Attributes::fromArray(
                [
                    Html5::ATTR_CLASS => [static::FILE_CATEGORY_CLASS, $categoryIdentifier],
                ]
            )
        );
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param string[] $identifiers
     * @param string   $cacheTime
     *
     * @return bool
     */
    public function hasAnyChangedSince(array $identifiers, string $cacheTime): bool
    {
        return true;
    }
}
