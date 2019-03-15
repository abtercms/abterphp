<?php

declare(strict_types=1);

namespace AbterPhp\Framework\I18n;

use Opulence\Sessions\ISession;

class Translator implements ITranslator
{
    /** @var array */
    protected $translations = [];

    /** @var ISession */
    protected $session;

    /** @var string */
    protected $translationsDir;

    /** @var string */
    protected $defaultLang;

    /** @var string */
    protected $lang;

    /** @var bool */
    protected $isInitialized = false;

    /**
     * Translator constructor.
     *
     * @param ISession $session
     * @param string   $translationsDir
     * @param string   $defaultLang
     */
    public function __construct(ISession $session, string $translationsDir, string $defaultLang)
    {
        $this->session         = $session;
        $this->translationsDir = $translationsDir;
        $this->defaultLang     = $defaultLang;
    }

    /**
     * @return string
     */
    private function getLang(): string
    {
        if ($this->lang) {
            return $this->lang;
        }

        $this->lang = $this->defaultLang;

        if ($this->session->has(SESSION_LANGUAGE_IDENTIFIER)) {
            $this->lang = (string)$this->session->get(SESSION_LANGUAGE_IDENTIFIER);
        }

        return $this->lang;
    }

    public function initialize()
    {
        $lang = $this->getLang();
        $dir  = sprintf('%s/%s/', $this->translationsDir, $lang);

        foreach (scandir($dir) as $file) {
            if (strlen($file) < 4 || substr($file, -4) !== '.php') {
                continue;
            }

            $content = require $dir . $file;
            $this->setTranslations($content, substr($file, 0, -4), $lang);
        }

        $this->isInitialized = true;
    }

    /**
     * @param array  $translations
     * @param string $key
     * @param string $lang
     */
    public function setTranslations(array $translations, string $key = '', string $lang = 'en')
    {
        if ('' === $key) {
            $this->translations[$lang] = $translations;

            return;
        }

        $this->translations[$lang][$key] = $translations;
    }

    /**
     * @param string $key
     * @param array  ...$args
     *
     * @return string
     */
    public function translate(string $key, ...$args): string
    {
        if (!$this->isInitialized) {
            $this->initialize();
        }

        return $this->translateByArgs($key, $args);
    }

    /**
     * @param string $key
     * @param array  ...$args
     *
     * @return string
     */
    public function canTranslate(string $key, ...$args): bool
    {
        if (!$this->isInitialized) {
            $this->initialize();
        }

        $res = $this->translateByArgs($key, $args);

        if (strpos($res, '{{translation ') !== 0) {
            return true;
        }

        return substr($res, -2) !== '}}';
    }

    /**
     * @param string $key
     * @param array  $args
     *
     * @return string
     */
    public function translateByArgs(string $key, array $args = []): string
    {
        if (!$this->isInitialized) {
            $this->initialize();
        }

        $pathParts = explode(':', $key);

        $translations = &$this->translations[$this->lang];
        foreach ($pathParts as $pathPart) {
            if (!array_key_exists($pathPart, $translations)) {
                return "{{translation missing: $key}}";
            }

            $translations = &$translations[$pathPart];
        }

        if (!is_string($translations)) {
            return "{{translation is ambiguous: $key}}";
        }

        foreach ($args as $argKey => $argValue) {
            $argTranslation = $this->translateByArgs($argValue);

            if (substr($argTranslation, 0, 2) === '{{') {
                continue;
            }

            $args[$argKey] = $argTranslation;
        }

        return vsprintf($translations, $args);
    }
}
