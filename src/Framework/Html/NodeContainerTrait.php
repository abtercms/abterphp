<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html;

trait NodeContainerTrait
{
    /**
     * @param int $depth
     *
     * @return INode[]
     */
    public function getAllNodes(int $depth = -1): array
    {
        $nodes = [];
        foreach ($this->getNodes() as $v) {
            $nodes[] = $v;
            if ($depth !== 0 && $v instanceof INodeContainer) {
                $nodes = array_merge($nodes, $v->getAllNodes($depth - 1));
            }
        }

        return $nodes;
    }
}
