<?php
use Opulence\Cache\FileBridge;
use Opulence\Databases\Adapters\Pdo\MySql\Driver;
use Opulence\Environments\Environment;
use Opulence\Sessions\Handlers\FileSessionHandler;
use Opulence\Views\Caching\FileCache;

/**
 * ----------------------------------------------------------
 * Set environment metadata
 * ----------------------------------------------------------
 */
Environment::setVar("ENV_NAME", Environment::DEVELOPMENT);

/**
 * ----------------------------------------------------------
 * Set the session handler and cache bridge
 * ----------------------------------------------------------
 */
Environment::setVar("SESSION_HANDLER", FileSessionHandler::class);
Environment::setVar("SESSION_CACHE_BRIDGE", FileBridge::class);
Environment::setVar("SESSION_COOKIE_DOMAIN", "");
Environment::setVar("SESSION_COOKIE_IS_SECURE", false);
Environment::setVar("SESSION_COOKIE_PATH", "/");

/**
 * ----------------------------------------------------------
 * Set the view cache
 * ----------------------------------------------------------
 */
Environment::setVar("VIEW_CACHE", FileCache::class);

/**
 * ----------------------------------------------------------
 * Set SQL database connection info
 * ----------------------------------------------------------
 */
Environment::setVar('DB_DRIVER', Driver::class);
Environment::setVar("DB_HOST", "localhost");
Environment::setVar("DB_USER", "myuser");
Environment::setVar("DB_PASSWORD", "mypassword");
Environment::setVar("DB_NAME", "public");
Environment::setVar("DB_PORT", 5432);

/**
 * ----------------------------------------------------------
 * Set Memcached connection info
 * ----------------------------------------------------------
 */
Environment::setVar("MEMCACHED_HOST", "localhost");
Environment::setVar("MEMCACHED_PORT", 11211);

/**
 * ----------------------------------------------------------
 * Set Redis connection info
 * ----------------------------------------------------------
 */
Environment::setVar("REDIS_HOST", "localhost");
Environment::setVar("REDIS_PORT", 6379);
Environment::setVar("REDIS_DATABASE", 0);

/**
 * ----------------------------------------------------------
 * Set the encryption key
 * ----------------------------------------------------------
 */
Environment::setVar("ENCRYPTION_KEY", "def00000cea4c75b84279f43b56dd90851609717c5d29c215fd2c67f9b1acb0c3b1c5ff8528dbeecf0c1f368baa33284aa36d00b24994872970933e8881802287553ff7d");

/**
 * ----------------------------------------------------------
 * Set the default language
 * ----------------------------------------------------------
 */
Environment::setVar("DEFAULT_LANGUAGE", "en");

/**
 * ----------------------------------------------------------
 * Set cryptography options
 * ----------------------------------------------------------
 */
Environment::setVar("CRYPTO_FRONTEND_SALT", "");
Environment::setVar("CRYPTO_ENCRYPTION_PEPPER", "");

/**
 * ----------------------------------------------------------
 * Set directory paths
 * ----------------------------------------------------------
 */
Environment::setVar("DIR_PRIVATE", "/path/to/root/private");
Environment::setVar("DIR_PUBLIC", "/path/to/root/public");
Environment::setVar("DIR_AUTH_CONFIG", "/path/to/root/config/authorization");
Environment::setVar("DIR_MIGRATIONS", "/path/to/root/resources/migrations");
Environment::setVar("DIR_LOGS", "/path/to/root/tmp/logs");

/**
 * ----------------------------------------------------------
 * Set Oauth2 options
 * ----------------------------------------------------------
 */
Environment::setVar("OAUTH2_PRIVATE_KEY_PATH", "");
Environment::setVar("OAUTH2_PRIVATE_KEY_PASSWORD", "");
Environment::setVar("OAUTH2_PUBLIC_KEY_PATH", "");
Environment::setVar("OAUTH2_ENCRYPTION_KEY", "");
Environment::setVar("OAUTH2_TOKEN_EXPIRY", "PT1H");
Environment::setVar("OAUTH2_SECRET_LENGTH", 16);

/**
 * ----------------------------------------------------------
 * Set login options
 * ----------------------------------------------------------
 */
Environment::setVar("LOGIN_MAX_ATTEMPTS", "10");
Environment::setVar("LOGIN_LOG_IP", "0");

/**
 * ----------------------------------------------------------
 * Set pagination options
 * ----------------------------------------------------------
 */
Environment::setVar("PAGINATION_SIZE_OPTIONS", "10,25,50,200,500");
Environment::setVar("PAGINATION_SIZE_DEFAULT", "50");
Environment::setVar("PAGINATION_NUMBER_COUNT", "5");

/**
 * ----------------------------------------------------------
 * Set general website settings
 * ----------------------------------------------------------
 */
Environment::setVar("WEBSITE_BASE_URL", "https://abtercms.test/");
Environment::setVar("WEBSITE_SITE_TITLE", "AbterCMS Test Site");

/**
 * ----------------------------------------------------------
 * Email settings
 * ----------------------------------------------------------
 */

Environment::setVar("EMAIL_SMTP_HOST", "mailcatcher");
Environment::setVar("EMAIL_SMTP_PORT", "1025");
Environment::setVar("EMAIL_SMTP_ENCRYPTION", "");
Environment::setVar("EMAIL_SMTP_USERNAME", "");
Environment::setVar("EMAIL_SMTP_PASSWORD", "");

/**
 * ----------------------------------------------------------
 * Admin settings
 * ----------------------------------------------------------
 */
Environment::setVar("ADMIN_DATE_FORMAT", "Y-m-d");
Environment::setVar("ADMIN_DATETIME_FORMAT", "Y-m-d H:i:s");
Environment::setVar("ADMIN_BASE_PATH", "/admin-iddqd");
Environment::setVar("ADMIN_LOGIN_PATH", "/login-iddqd");

/**
 * ----------------------------------------------------------
 * API settings
 * ----------------------------------------------------------
 */

Environment::setVar("API_BASE_PATH", "/api-iddqd");
Environment::setVar("API_PROBLEM_BASE_URL", "https://abtercms.com/problem/");

/**
 * ----------------------------------------------------------
 * Cache settings
 * ----------------------------------------------------------
 */

Environment::setVar("MODULE_CACHE_KEY", "AbterPhp:Modules");

/**
 * ----------------------------------------------------------
 * Oauth2 settings
 * ----------------------------------------------------------
 */

Environment::setVar("OAUTH2_PRIVATE_KEY_PATH", "");
Environment::setVar("OAUTH2_PRIVATE_KEY_PASSWORD", "");
Environment::setVar("OAUTH2_PUBLIC_KEY_PATH", "");
Environment::setVar("OAUTH2_ENCRYPTION_KEY", "");
Environment::setVar("OAUTH2_TOKEN_EXPIRY", "");
Environment::setVar("OAUTH2_SECRET_LENGTH", "");

