<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Helper;

class ArrayHelper
{
    /**
     * @param \DateTime $dateTime
     *
     * @return string
     */
    public static function flatten(array $errors): array
    {
        $result = [];

        foreach ($errors as $error) {
            if (is_scalar($error)) {
                $result[] = $error;
            } else {
                $result = array_merge($result, self::flatten($error));
            }
        }

        return $result;
    }

    /**
     * @param array $styles
     *
     * @return string
     */
    public static function toStyles(array $styles): string
    {
        $tmp = [];
        foreach ($styles as $k => $v) {
            $tmp[] = sprintf('%s: %s', $k, $v);
        }

        return implode('; ', $tmp);
    }

    /**
     * @param array  $attributes
     * @param string $prepend string to prepend if the result is not empty
     *
     * @return string
     */
    public static function toAttributes(array $attributes, string $prepend = ' '): string
    {
        $tmp = [];
        foreach ($attributes as $k => $v) {
            if (null === $v) {
                $tmp[] = (string)$k;
                continue;
            }

            $v = is_array($v) ? implode(' ', $v) : (string)$v;

            $tmp[] = sprintf('%s="%s"', $k, $v);
        }

        if (empty($tmp)) {
            return '';
        }

        return $prepend . implode(' ', $tmp);
    }

    /**
     * @param array $existingAttributes
     * @param array $newAttributes
     *
     * @return array
     */
    public static function mergeAttributes(array $existingAttributes, array $newAttributes): array
    {
        foreach ($newAttributes as $key => $value) {
            if (array_key_exists($key, $existingAttributes)) {
                $existingAttributes[$key] = array_merge((array)$existingAttributes[$key], (array)$value);
            } else {
                $existingAttributes[$key] = $value;
            }
        }

        return $existingAttributes;
    }

    /**
     * @param array $parts
     *
     * @return string
     */
    public static function toQuery(array $parts): string
    {
        if (empty($parts)) {
            return '';
        }

        $tmp = [];
        foreach ($parts as $k => $v) {
            $tmp[] = sprintf('%s=%s', $k, urlencode($v));
        }

        return '?' . implode('&', $tmp);
    }
}
