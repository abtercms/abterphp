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
     * @param array $attributes
     *
     * @return string
     */
    public static function toAttributes(array $attributes): string
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

        return implode(' ', $tmp);
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
