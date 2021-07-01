<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html\Factory;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Html\Attribute;
use AbterPhp\Framework\Html\Component\Button as ButtonComponent;
use AbterPhp\Framework\Html\Component\ButtonWithIcon;
use AbterPhp\Framework\Html\Helper\Attributes;
use AbterPhp\Framework\Html\Tag;
use Opulence\Routing\Urls\UrlException;
use Opulence\Routing\Urls\UrlGenerator;

class Button
{
    /** @var UrlGenerator */
    protected UrlGenerator $urlGenerator;

    /** @var array<string,Attribute> */
    protected array $attributes;

    /** @var array<string,Attribute> */
    protected array $iconAttributes;

    /** @var array<string,Attribute> */
    protected array $textAttributes;

    protected string $iconTag = Html5::TAG_I;
    protected string $textTag = Html5::TAG_SPAN;

    /**
     * ButtonFactory constructor.
     *
     * @param UrlGenerator        $urlGenerator
     * @param array<string,mixed> $textAttributes
     * @param array<string,mixed> $iconAttributes
     * @param array<string,mixed> $attributes
     * @param string              $textTag
     * @param string              $iconTag
     */
    public function __construct(
        UrlGenerator $urlGenerator,
        array $textAttributes = [],
        array $iconAttributes = [],
        array $attributes = [],
        string $textTag = Html5::TAG_SPAN,
        string $iconTag = Html5::TAG_I
    ) {
        $this->urlGenerator   = $urlGenerator;
        $this->textAttributes = $textAttributes ? Attributes::fromArray($textAttributes) : [];
        $this->iconAttributes = $iconAttributes ? Attributes::fromArray($iconAttributes) : [];
        $this->attributes     = $attributes ? Attributes::fromArray($attributes) : [];
        $this->iconTag        = $iconTag;
        $this->textTag        = $textTag;
    }

    /**
     * @param string              $text
     * @param string              $url
     * @param string              $icon
     * @param array<string,mixed> $textAttributes
     * @param array<string,mixed> $iconAttributes
     * @param string[]            $intents
     * @param array<string,mixed> $attributes
     * @param string|null         $tag
     *
     * @return ButtonComponent
     */
    public function createFromUrl(
        string $text,
        string $url,
        string $icon = '',
        array $textAttributes = [],
        array $iconAttributes = [],
        array $intents = [],
        array $attributes = [],
        ?string $tag = Html5::TAG_A
    ): ButtonComponent {
        $attributes = $attributes ? Attributes::fromArray($attributes) : [];
        $attributes = Attributes::addItem($attributes, Html5::ATTR_HREF, $url);

        if ($icon) {
            return $this->createWithIcon($text, $icon, $textAttributes, $iconAttributes, $intents, $attributes, $tag);
        }

        return $this->createSimple($text, $intents, $attributes, $tag);
    }

    /**
     * @suppress PhanTypeMismatchArgument issue with Opulence\Routing\Urls\UrlGenerator::createFromName
     *
     * @param string              $text
     * @param string              $urlName
     * @param string[]            $urlArgs
     * @param string              $icon
     * @param array<string,mixed> $textAttributes
     * @param array<string,mixed> $iconAttributes
     * @param string[]            $intents
     * @param array<string,mixed> $attributes
     * @param string|null         $tag
     *
     * @return ButtonComponent
     * @throws URLException
     */
    public function createFromName(
        string $text,
        string $urlName,
        array $urlArgs,
        string $icon = '',
        array $textAttributes = [],
        array $iconAttributes = [],
        array $intents = [],
        array $attributes = [],
        ?string $tag = Html5::TAG_A
    ): ButtonComponent {
        $url = $this->urlGenerator->createFromName($urlName, ...$urlArgs);

        $attributes = $attributes ? Attributes::fromArray($attributes) : [];
        $attributes = Attributes::addItem($attributes, Html5::ATTR_HREF, $url);

        if ($icon) {
            return $this->createWithIcon($text, $icon, $textAttributes, $iconAttributes, $intents, $attributes, $tag);
        }

        return $this->createSimple($text, $intents, $attributes, $tag);
    }

    /**
     * @param string                  $text
     * @param string[]                $intents
     * @param array<string,Attribute> $attributes
     * @param string|null             $tag
     *
     * @return ButtonComponent
     */
    public function createSimple(
        string $text,
        array $intents = [],
        array $attributes = [],
        ?string $tag = null
    ): ButtonComponent {
        $attributes = Attributes::merge($attributes, $this->attributes);

        return new ButtonComponent($text, $intents, $attributes, $tag);
    }

    /**
     * @param string              $text
     * @param string              $icon
     * @param array<string,mixed> $textAttributes
     * @param array<string,mixed> $iconAttributes
     * @param string[]            $intents
     * @param array<string,mixed> $attributes
     * @param string|null         $tag
     *
     * @return ButtonWithIcon
     */
    public function createWithIcon(
        string $text,
        string $icon,
        array $textAttributes = [],
        array $iconAttributes = [],
        array $intents = [],
        array $attributes = [],
        ?string $tag = null
    ): ButtonWithIcon {
        $iconAttributes = Attributes::merge(Attributes::fromArray($iconAttributes), $this->iconAttributes);
        $textAttributes = Attributes::merge(Attributes::fromArray($textAttributes), $this->textAttributes);

        $textComponent = new Tag($text, [], $textAttributes, $this->textTag);
        $iconComponent = new Tag($icon, [], $iconAttributes, $this->iconTag);

        $attributes = Attributes::merge(Attributes::fromArray($attributes), $this->attributes);

        return new ButtonWithIcon($textComponent, $iconComponent, $intents, $attributes, $tag);
    }
}
