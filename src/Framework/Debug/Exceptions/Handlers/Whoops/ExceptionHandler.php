<?php

namespace AbterPhp\Framework\Debug\Exceptions\Handlers\Whoops;

use Monolog\Logger;
use Opulence\Debug\Exceptions\Handlers\IExceptionHandler;
use Throwable;

/**
 * @SuppressWarnings(PHPMD)
 */
class ExceptionHandler implements IExceptionHandler
{
    /** @var Logger */
    protected $logger;

    /** @var ExceptionRenderer */
    protected $whoopsRenderer;

    /**
     * @param Logger            $logger
     * @param ExceptionRenderer $whoopsRenderer
     * @param array             $exceptionsSkipped
     */
    public function __construct(Logger $logger, ExceptionRenderer $whoopsRenderer, array $exceptionsSkipped)
    {
        $this->logger         = $logger;
        $this->whoopsRenderer = $whoopsRenderer;
    }

    /**
     * Handles an exception
     *
     * @param Throwable $ex The exception to handle
     */
    public function handle($ex)
    {
        $this->whoopsRenderer->render($ex);
    }

    /**
     * Registers the handler with PHP
     */
    public function register()
    {
        $whoops = $this->whoopsRenderer->getRun();

        $whoops->pushHandler(new \Whoops\Handler\PlainTextHandler($this->logger));
        if (php_sapi_name() !== 'cli') {
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
        }

        $whoops->register();
    }
}
