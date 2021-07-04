<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm;

use AbterPhp\Framework\Orm\GridRepo;
use AbterPhp\Website\Domain\Entities\PageLayout as Entity;
use QB\Generic\Statement\ISelect;

// @phan-suppress-current-line PhanUnreferencedUseNormal

class PageLayoutRepo extends GridRepo
{
    /**
     * @param string $identifier
     *
     * @return Entity|null
     */
    public function getByIdentifier(string $identifier): ?Entity
    {
        return $this->getOne(['identifier' => $identifier]);
    }

    /**
     * @return array<string,string>
     */
    public function getDefaultSorting(): array
    {
        return [
            'page_layouts.name' => ISelect::DIRECTION_ASC,
        ];
    }

    /**
     * @param array $row
     *
     * @return Entity
     */
    public function createEntity(array $row): Entity
    {
        $assets = $this->loadAssets($row);

        return new Entity(
            $row['id'],
            $row['name'],
            $row['identifier'],
            $row['classes'],
            $row['body'],
            $assets
        );
    }

    /**
     * @param array $row
     *
     * @return Entity\Assets|null
     */
    protected function loadAssets(array $row): ?Entity\Assets
    {
        return new Entity\Assets(
            $row['identifier'],
            $row['header'],
            $row['footer'],
            explode("\r\n", $row['css_files']),
            explode("\r\n", $row['js_files'])
        );
    }
}
