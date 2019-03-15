<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html\Component;

use AbterPhp\Framework\Html\Collection\Options;
use AbterPhp\Framework\I18n\ITranslator;

class Select extends Component
{
    const TAG_SELECT = 'select';

    const ATTRIBUTE_NAME = 'name';

    /** @var Options */
    protected $options;

    /**
     * Select constructor.
     *
     * @param string $name
     * @param array  $options
     */
    public function __construct(
        string $name,
        array $options,
        string $selected = '',
        string $tag = null,
        array $attributes = [],
        ITranslator $translator = null
    ) {
        $attributes[static::ATTRIBUTE_NAME] = $name;

        $collection = new Options();
        foreach ($options as $value => $content) {
            $optionAttributes = $this->getOptionAttributes((string)$value, $selected);

            $collection[] = new Option((string)$content, Option::TAG_OPTION, $optionAttributes);
        }

        parent::__construct('', $tag, $attributes, $translator);

        $this->options = $collection;
    }

    /**
     * @param string $value
     * @param string $selected
     *
     * @return array
     */
    protected function getOptionAttributes(string $value, string $selected): array
    {
        $optionAttributes = [];

        $optionAttributes[Option::ATTRIBUTE_VALUE] = $value;

        if ($value === $selected) {
            $optionAttributes[Option::ATTRIBUTE_SELECTED] = Option::ATTRIBUTE_SELECTED;
        }

        return $optionAttributes;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        foreach ($this->options as $option) {
            $this->content .= (string)$option;
        }

        return parent::__toString();
    }
}
