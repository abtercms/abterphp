<?php

declare(strict_types=1);

namespace AbterPhp\Website\Http\Controllers\Api;

use AbterPhp\Admin\Http\Controllers\ApiAbstract;

class PageLayout extends ApiAbstract
{
    public const ENTITY_SINGULAR = 'pageLayout';
    public const ENTITY_PLURAL   = 'pageLayouts';

    /**
     * @return array
     */
    public function getSharedData(): array
    {
        $data = $this->request->getJsonBody();

        $data['assets'] = !empty($data['assets']) ? $data['assets'] : [];

        $data = array_merge($data, $data['assets']);

        unset($data['assets']);

        return $data;
    }
}
