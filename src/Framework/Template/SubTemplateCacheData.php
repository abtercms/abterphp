<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Template;

class SubTemplateCacheData
{
    const PAYLOAD_KEY_DATE         = 'date';
    const PAYLOAD_KEY_SUBTEMPLATES = 'subTemplates';

    /** @var string */
    private $date = '';

    /** @var string[][] */
    private $subTemplates = [];

    public function __construct()
    {
        $this->date = date('Y-m-d H:i:s');
    }

    /**
     * @param string $date
     *
     * @return $this
     */
    public function setDate(string $date): SubTemplateCacheData
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @param array $subTemplates
     *
     * @return $this
     */
    public function setSubTemplates(array $subTemplates): SubTemplateCacheData
    {
        $this->subTemplates = $subTemplates;

        return $this;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @return string[][]
     */
    public function getSubTemplates(): array
    {
        return $this->subTemplates;
    }

    /**
     * @return string
     */
    public function toPayload(): string
    {
        return json_encode([
            static::PAYLOAD_KEY_DATE         => $this->getDate(),
            static::PAYLOAD_KEY_SUBTEMPLATES => $this->getSubTemplates(),
        ]);
    }

    /**
     * @param array $payload
     *
     * @return SubTemplateCacheData
     */
    public static function fromPayload(string $payload): SubTemplateCacheData
    {
        $data = json_decode($payload, true);

        $subTemplateCacheData = new SubTemplateCacheData();

        if (!is_array($data)) {
            return $subTemplateCacheData;
        }

        if (array_key_exists(static::PAYLOAD_KEY_DATE, $data)) {
            $subTemplateCacheData->date = $data[static::PAYLOAD_KEY_DATE];
        }

        if (array_key_exists(static::PAYLOAD_KEY_SUBTEMPLATES, $data)) {
            $subTemplateCacheData->subTemplates = $data[static::PAYLOAD_KEY_SUBTEMPLATES];
        }

        return $subTemplateCacheData;
    }
}
