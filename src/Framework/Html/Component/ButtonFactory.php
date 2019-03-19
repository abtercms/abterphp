<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html\Component;

use AbterPhp\Framework\Helper\ArrayHelper;
use AbterPhp\Framework\I18n\ITranslator;
use Opulence\Routing\Urls\UrlException;
use Opulence\Routing\Urls\UrlGenerator;

class ButtonFactory
{
    /** @var UrlGenerator */
    protected $urlGenerator;

    /** @var array */
    protected $iconAttributes = [];

    /** @var array */
    protected $textAttributes = [];

    /** @var array */
    protected $attributes = [];

    /** @var string */
    protected $iconTag = 'i';

    /** @var string */
    protected $textTag = 'span';

    /**
     * ButtonFactory constructor.
     *
     * @param UrlGenerator $urlGenerator
     * @param array        $iconAttributes
     * @param array        $textAttributes
     * @param array        $attributes
     * @param string       $iconTag
     * @param string       $textTag
     */
    public function __construct(
        UrlGenerator $urlGenerator,
        array $iconAttributes = [],
        array $textAttributes = [],
        array $attributes = [],
        string $iconTag = 'i',
        string $textTag = 'span'
    ) {
        $this->urlGenerator   = $urlGenerator;
        $this->iconAttributes = $iconAttributes;
        $this->textAttributes = $textAttributes;
        $this->attributes     = $attributes;
        $this->iconTag        = $iconTag;
        $this->textTag        = $textTag;
    }

    /**
     * @param string           $text
     * @param string           $url
     * @param string           $icon
     * @param array            $textAttribs
     * @param array            $iconAttribs
     * @param array            $attribs
     * @param ITranslator|null $translator
     * @param string|null      $tag
     *
     * @return Button
     */
    public function createFromUrl(
        string $text,
        string $url,
        string $icon = '',
        array $textAttribs = [],
        array $iconAttribs = [],
        $attribs = [],
        ?ITranslator $translator = null,
        ?string $tag = Button::TAG_A
    ): Button {
        $attribs[Button::ATTRIBUTE_HREF] = $url;

        if ($icon) {
            return $this->createWithIcon($text, $icon, $textAttribs, $iconAttribs, $attribs, $translator, $tag);
        }

        return $this->createSimple($text, $attribs, $translator, $tag);
    }

    /**
     * @param string           $text
     * @param string           $urlName
     * @param array            $urlArgs
     * @param string           $icon
     * @param array            $textAttribs
     * @param array            $iconAttribs
     * @param array            $attribs
     * @param ITranslator|null $translator
     * @param string|null      $tag
     *
     * @return Button
     */
    public function createFromName(
        string $text,
        string $urlName,
        array $urlArgs,
        string $icon = '',
        array $textAttribs = [],
        array $iconAttribs = [],
        $attribs = [],
        ?ITranslator $translator = null,
        ?string $tag = Button::TAG_A
    ): Button {
        try {
            $url = $this->urlGenerator->createFromName($urlName, ...$urlArgs);
        } catch (UrlException $e) {
            $url = '';
        }

        $attribs[Button::ATTRIBUTE_HREF] = $url;

        if ($icon) {
            return $this->createWithIcon($text, $icon, $textAttribs, $iconAttribs, $attribs, $translator, $tag);
        }

        return $this->createSimple($text, $attribs, $translator, $tag);
    }

    /**
     * @param string           $text
     * @param array            $attributes
     * @param ITranslator|null $translator
     * @param string|null      $tag
     *
     * @return Button
     */
    protected function createSimple(
        string $text,
        $attributes,
        ?ITranslator $translator,
        ?string $tag
    ): Button {
        $attributes = ArrayHelper::mergeAttributes($this->attributes, $attributes);

        $linkComponent = new Button($text, $attributes, $translator, $tag);

        return $linkComponent;
    }

    /**
     * @param string           $text
     * @param string           $icon
     * @param array            $textAttribs
     * @param array            $iconAttribs
     * @param array            $attributes
     * @param ITranslator|null $translator
     * @param string|null      $tag
     *
     * @return ButtonWithIcon
     */
    protected function createWithIcon(
        string $text,
        string $icon,
        array $textAttribs,
        array $iconAttribs,
        $attributes,
        ?ITranslator $translator,
        string $tag
    ): ButtonWithIcon {
        $iconAttribs =  ArrayHelper::mergeAttributes($this->iconAttributes, $iconAttribs);
        $textAttribs =  ArrayHelper::mergeAttributes($this->textAttributes, $textAttribs);

        $textComponent = new Tag($text, $textAttribs, $translator, $this->textTag);
        $iconComponent = new Tag($icon, $iconAttribs, $translator, $this->iconTag);

        $attributes = ArrayHelper::mergeAttributes($this->attributes, $attributes);

        $linkComponent = new ButtonWithIcon($textComponent, $iconComponent, $attributes, $translator, $tag);

        return $linkComponent;
    }
}
