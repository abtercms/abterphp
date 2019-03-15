<?php

declare(strict_types=1);

use Monolog\Logger;

/**
 * ----------------------------------------------------------
 * Create a PSR-3 logger
 * ----------------------------------------------------------
 *
 * Note: You may use any PSR-3 logger you'd like
 * For convenience, the Monolog library is included here
 */
$logger = new Logger('system');
$filePath = getenv(\AbterPhp\Framework\Constant\Env::DIR_LOGS);
$logger->pushHandler(new \Monolog\Handler\StreamHandler($filePath . '/system.log', Logger::INFO));

return $logger;
