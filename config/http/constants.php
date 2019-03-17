<?php

declare(strict_types=1);

if (!defined('PATH_LOGIN')) {
    define('PATH_LOGIN', getenv('ADMIN_LOGIN_PATH'));
}

if (!defined('PATH_ADMIN')) {
    define('PATH_ADMIN', getenv('ADMIN_BASE_PATH'));
}

if (!defined('PATH_API')) {
    define('PATH_API', getenv('API_BASE_PATH'));
}

const OPTION_NAME       = 'name';
const OPTION_VARS       = 'vars';
const OPTION_MIDDLEWARE = 'middleware';
