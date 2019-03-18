<?php

namespace AbterPhp\Framework\Html\Component;

class StubAttributeFactory
{
    /**
     * @param array $extraAttributes
     *
     * @return array
     */
    public static function createAttributes(array $extraAttributes = []): array
    {
        $attributes = [
            TagTest::ATTRIBUTE_FOO => [TagTest::VALUE_FOO, TagTest::VALUE_BAZ],
            TagTest::ATTRIBUTE_BAR => TagTest::VALUE_BAR_BAZ,
            Tag::ATTRIBUTE_CLASS   => TagTest::VALUE_FOO,
        ];

        foreach ($extraAttributes as $key => $values) {
            if (!array_key_exists($key, $attributes)) {
                $attributes[$key] = $attributes;
                continue;
            }

            $attributes[$key] = array_merge((array)$attributes[$key], (array)$values);
        }

        return $attributes;
    }
}
