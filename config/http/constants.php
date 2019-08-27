<?php

declare(strict_types=1);

use Opulence\Environments\Environment;
const OPTION_NAME       = 'name';
const OPTION_VARS       = 'vars';
const OPTION_MIDDLEWARE = 'middleware';

const ADMIN_LOGIN_PATH = 'ADMIN_LOGIN_PATH';
const ADMIN_BASE_PATH  = 'ADMIN_BASE_PATH';
const API_BASE_PATH    = 'API_BASE_PATH';

if (!defined('PATH_LOGIN')) {
    define('PATH_LOGIN', Environment::getVar(ADMIN_LOGIN_PATH));
}

if (!defined('PATH_ADMIN')) {
    define('PATH_ADMIN', Environment::getVar(ADMIN_BASE_PATH));
}

if (!defined('PATH_API')) {
    define('PATH_API', Environment::getVar(API_BASE_PATH));
}
