<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Events;

use AbterPhp\Framework\Template\TemplateEngine;

class TemplateEngineReady
{
    /** @var TemplateEngine */
    private $engine;

    /**
     * TemplateEngineReady constructor.
     *
     * @param TemplateEngine $adapter
     */
    public function __construct(TemplateEngine $engine)
    {
        $this->engine = $engine;
    }

    /**
     * @return TemplateEngine
     */
    public function getTemplateEngine(): TemplateEngine
    {
        return $this->engine;
    }
}
