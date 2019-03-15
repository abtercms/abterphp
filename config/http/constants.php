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

const SESSION_USERNAME            = 'username';
const SESSION_EMAIL               = 'email';
const SESSION_USER_ID             = 'user_id';
const SESSION_IS_GRAVATAR_ALLOWED = 'is_gravatar_allowed';
const SESSION_LANGUAGE_IDENTIFIER = 'language_identifier';
const SESSION_IS_LOGGED_IN        = 'is_user';
const SESSION_CATEGORIES          = 'categories';
const SESSION_LAST_GRID_URL       = 'last_grid_url';
