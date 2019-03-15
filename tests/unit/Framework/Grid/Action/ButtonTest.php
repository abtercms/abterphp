<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Grid\Action;

use AbterPhp\Framework\I18n\ITranslatorMockTrait;
use PHPUnit\Framework\TestCase;

class ButtonTest extends TestCase
{
    use ITranslatorMockTrait;

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
        $sut = $this->createElement('', '', [], [], null);

        $this->markTestIncomplete();
    }

    /**
     * @param string     $content
     * @param string     $tag
     * @param array      $attributes
     * @param array      $attributeCallbacks
     * @param array|null $translations
     *
     * @return Button
     */
    private function createElement(
        string $content,
        string $tag,
        array $attributes,
        array $attributeCallbacks,
        ?array $translations
    ): Button {
        $translatorMock = $this->getTranslatorMock($translations);

        return new Button($content, $tag, $attributes, $attributeCallbacks, $translatorMock);
    }
}
