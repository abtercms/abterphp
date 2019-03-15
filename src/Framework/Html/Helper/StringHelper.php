<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html\Helper;

use AbterPhp\Framework\Helper\ArrayHelper;
use AbterPhp\Framework\Html\Component\IComponent;

class StringHelper
{
    /**
     * @param string|IComponent $content
     * @param string|null       $tag
     * @param array             $attributes
     * @param string            $whitespace
     *
     * @return string
     */
    public static function wrapInTag($content, string $tag = null, array $attributes = [], $whitespace = '')
    {
        if (null === $tag) {
            return (string)$content;
        }

        $attributeHtml = ArrayHelper::toAttributes($attributes);
        $attributeHtml = $attributeHtml ? ' ' . $attributeHtml : '';

        if ($whitespace) {
            return sprintf(
                '%4$s<%1$s%3$s>%5$s%2$s%5$s%4$s</%1$s>',
                $tag,
                (string)$content,
                $attributeHtml,
                $whitespace,
                "\n"
            );
        }

        return sprintf('<%1$s%3$s>%2$s</%1$s>', $tag, (string)$content, $attributeHtml);
    }

    /**
     * @param string $tag
     * @param array  $attributes
     *
     * @return string
     */
    public static function createTag(string $tag, array $attributes = [])
    {
        $attributeHtml = '';

        foreach ($attributes as $key => $value) {
            if (is_array($value)) {
                $value = implode(' ', $value);
            }
            $attributeHtml .= sprintf(' %s="%s"', $key, $value);
        }

        return sprintf('<%1$s%2$s>', $tag, $attributeHtml);
    }
}
