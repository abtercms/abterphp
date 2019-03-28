<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html;

interface INodeContainer
{
    /**
     * @return INode[]
     */
    public function getNodes(): array;

    /**
     * @param int $depth
     *
     * @return INode[]
     */
    public function getAllNodes(int $depth = -1): array;
}
