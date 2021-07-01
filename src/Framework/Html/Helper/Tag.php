<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html\Helper;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Html\Attribute;

class Tag
{
    protected const SINGLE_TAGS      = [
        Html5::TAG_BR,
        Html5::TAG_HR,
        Html5::TAG_IMG,
        Html5::TAG_INPUT,
        Html5::TAG_LINK,
        Html5::TAG_META,
        Html5::TAG_SOURCE,
    ];
    protected const NO_CONTENT_TAG   = '<%1$s%2$s>';
    protected const WITH_CONTENT_TAG = '<%1$s%3$s>%2$s</%1$s>';

    /**
     * @param string                  $tag
     * @param string                  $content
     * @param array<string,Attribute> $attributes
     *
     * @return string
     */
    public static function toString(string $tag, string $content, array $attributes = []): string
    {
        assert($tag != '');
        assert(Attributes::isValid($attributes));

        $attributes = Attributes::toString($attributes);

        if (in_array($tag, self::SINGLE_TAGS, true)) {
            return sprintf(self::NO_CONTENT_TAG, $tag, $attributes);
        }

        return sprintf(self::WITH_CONTENT_TAG, $tag, $content, $attributes);
    }

    /**
     * @param string $text
     * @param string $tag
     *
     * @return string
     */
    public static function wrapByLines(string $text, string $tag): string
    {
        if (empty($text)) {
            return '';
        }

        $paragraphs = explode(PHP_EOL, $text);

        $lines = [];
        foreach ($paragraphs as $paragraph) {
            if (empty($paragraph)) {
                continue;
            }

            $lines[] = static::toString($tag, trim($paragraph));
        }

        return implode(PHP_EOL, $lines);
    }
}
