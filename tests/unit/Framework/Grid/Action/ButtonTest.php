<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Action;

use AbterPhp\Framework\I18n\MockTranslatorFactory;
use PHPUnit\Framework\TestCase;

class ButtonTest extends TestCase
{
    /** @var Button */
    protected $sut;

    public function testSetEntity()
    {
        $this->markTestIncomplete();
    }

    public function testRender()
    {
        $this->markTestIncomplete();
    }

    public function testDuplicate()
    {
        $this->markTestIncomplete();
    }

    public function testGetDefaultCallback()
    {
        $sut = $this->createElement('', [], [], null, null);

        $this->markTestIncomplete();
    }

    /**
     * @param string      $content
     * @param array       $attributes
     * @param array       $attributeCallbacks
     * @param array|null  $translations
     * @param string|null $tag
     *
     * @return Button
     */
    private function createElement(
        string $content,
        array $attributes,
        array $attributeCallbacks,
        ?array $translations,
        ?string $tag
    ): Button {
        $translatorMock = MockTranslatorFactory::createSimpleTranslator($this, $translations);

        return new Button($content, $attributes, $attributeCallbacks, $translatorMock, $tag);
    }
}
