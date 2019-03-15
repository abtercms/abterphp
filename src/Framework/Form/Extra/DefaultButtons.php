<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Form\Extra;

use AbterPhp\Framework\Html\Collection\Collection;
use AbterPhp\Framework\Html\Component\Button;
use AbterPhp\Framework\I18n\ITranslator;

class DefaultButtons extends Collection
{
    const DEFAULT_TAG = 'div';

    const DEFAULT_BTN_CLASS = 'btn pmd-checkbox-ripple-effect';

    const BTN_NAME_CONTINUE = 'continue';

    /** @var Button[] */
    protected $components;

    /** @var array */
    protected $attributes = [
        self::ATTRIBUTE_CLASS => 'form-group pmd-textfield pmd-textfield-floating-label',
    ];

    /** @var array */
    protected $btnAttributes = [
        Button::ATTRIBUTE_CLASS   => [self::DEFAULT_BTN_CLASS],
        Button::ATTRIBUTE_NAME  => self::BTN_NAME_CONTINUE,
        Button::ATTRIBUTE_TYPE  => Button::TYPE_SUBMIT,
        Button::ATTRIBUTE_VALUE => '0',
    ];

    /**
     * DefaultButtons constructor.
     *
     * @param string           $showUrl
     * @param string|null      $tag
     * @param array            $attributes
     * @param ITranslator|null $translator
     */
    public function __construct(
        string $showUrl,
        ?string $tag = null,
        array $attributes = [],
        ?ITranslator $translator = null
    ) {
        parent::__construct($tag, $attributes, $translator);

        $this->addSave();
        $this->addSaveAndEdit();
        $this->addBackToGrid($showUrl);
    }

    protected function addSave()
    {
        $attributes = $this->btnAttributes;

        $attributes[Button::ATTRIBUTE_CLASS][] = Button::CLASS_PRIMARY;

        $content = $this->translator->translate('framework:save');

        $this->components[] = new Button($content, Button::TAG_BUTTON, $attributes);
    }

    protected function addSaveAndEdit()
    {
        $attributes = $this->btnAttributes;

        $attributes[Button::ATTRIBUTE_CLASS][] = Button::CLASS_SUCCESS;
        $attributes[Button::ATTRIBUTE_VALUE] = '1';

        $content = $this->translator->translate('framework:saveAndEdit');

        $this->components[] = new Button($content, Button::TAG_BUTTON, $attributes);
    }

    /**
     * @param string $showUrl
     */
    protected function addBackToGrid(string $showUrl)
    {
        $attributes = [
            static::ATTRIBUTE_CLASS => static::DEFAULT_BTN_CLASS . ' ' . Button::CLASS_LINK,
            static::ATTRIBUTE_HREF  => $showUrl,
        ];

        $content = $this->translator->translate('framework:backToGrid');

        $this->components[] = new Button($content, Button::TAG_A, $attributes);
    }
}
