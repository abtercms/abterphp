<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Session;

use AbterPhp\Framework\Session\Helper\ArrayHelper;
use AbterPhp\Framework\I18n\ITranslator;
use Opulence\Sessions\ISession;

class FlashService
{
    const ERROR   = 'error';
    const SUCCESS = 'success';

    /** @var ISession */
    protected $session;

    /** @var ITranslator */
    protected $translator;

    /**
     * Helper constructor.
     *
     * @param ISession    $session
     * @param ITranslator $translator
     */
    public function __construct(ISession $session, ITranslator $translator)
    {
        $this->session    = $session;
        $this->translator = $translator;
    }

    /**
     * @param string[] $messages
     */
    public function mergeSuccessMessages(array $messages)
    {
        $currentMessages = (array)$this->session->get(static::SUCCESS);

        $newMessages = array_merge($currentMessages, $messages);

        $this->session->flash(static::SUCCESS, $newMessages);
    }

    /**
     * @param array  $messages
     * @param string $translationPrefix
     */
    public function mergeErrorMessages(array $messages, string $translationPrefix = '')
    {
        $messages = ArrayHelper::flatten($messages);

        if ($translationPrefix) {
            $messages = $this->translateMessages($messages, $translationPrefix);
        }

        $currentMessages = (array)$this->session->get(static::ERROR);

        $newMessages = array_merge($currentMessages, $messages);

        $this->session->flash(static::ERROR, $newMessages);
    }

    /**
     * @param array  $messages
     * @param string $translationPrefix
     *
     * @return array
     */
    protected function translateMessages(array $messages, string $translationPrefix = '')
    {
        if ($translationPrefix === '') {
            return $messages;
        }

        $translatedMessages = [];
        foreach ($messages as $fieldName => $fieldMessages) {
            if (!$this->translator->canTranslate($translationPrefix . $fieldName)) {
                continue;
            }
            $t = $this->translator->translate($translationPrefix . $fieldName);
            foreach ($fieldMessages as $idx => $message) {
                $translatedMessages[$fieldName][$idx] = str_replace("\"$fieldName\"", "\"$t\"", $message);
            }
        }

        return $translatedMessages;
    }

    /**
     * @param string   $key
     * @param string[] $value
     */
    public function mergeFlashMessages(string $key, array $value)
    {
        $currentValue = (array)$this->session->get($key);

        $newValue = array_merge($currentValue, $value);

        $this->session->flash($key, $newValue);
    }

    /**
     * @return array
     */
    public function retrieveSuccessMessages()
    {
        return (array)$this->session->get(static::SUCCESS);
    }

    /**
     * @return array
     */
    public function retrieveErrorMessages()
    {
        return (array)$this->session->get(static::ERROR);
    }

    /**
     * @param string $key
     *
     * @return array
     */
    public function retrieveFlashMessages(string $key)
    {
        return (array)$this->session->get($key);
    }
}
