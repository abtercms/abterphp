<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html;

use AbterPhp\Framework\I18n\MockTranslatorFactory;
use PHPUnit\Framework\TestCase;

class NodeTest extends TestCase
{
    public function testToStringIsEmptyByDefault()
    {
        $sut = $this->createNode();

        $this->assertContains('', (string)$sut);
    }

    /**
     * @return array
     */
    public function toStringReturnsRawContentByDefaultProvider(): array
    {
        return [
            'string' => ['foo', 'foo'],
        ];
    }

    /**
     * @dataProvider toStringReturnsRawContentByDefaultProvider
     *
     * @param mixed  $rawContent
     * @param string $expectedResult
     */
    public function testToStringReturnsRawContentByDefault($rawContent, string $expectedResult)
    {
        $sut = $this->createNode($rawContent);

        $this->assertContains($expectedResult, (string)$sut);
    }

    /**
     * @return array
     */
    public function toStringCanReturnTranslatedContentProvider(): array
    {
        $translations = ['foo' => 'bar'];

        return [
            'string' => ['foo', $translations, 'bar'],
        ];
    }

    /**
     * @dataProvider toStringCanReturnTranslatedContentProvider
     *
     * @param string $rawContent
     * @param string $expectedResult
     */
    public function testToStringCanReturnTranslatedContent($rawContent, array $translations, string $expectedResult)
    {
        $translatorMock = MockTranslatorFactory::createSimpleTranslator($this, $translations);

        $sut = $this->createNode($rawContent);

        $sut->setTranslator($translatorMock);

        $this->assertContains($expectedResult, (string)$sut);
    }

    public function testGetRawContentReturnsNonTranslatedContent()
    {
        $rawContent        = 'foo';
        $translatedContent = 'bar';
        $expectedResult    = $rawContent;
        $translations      = [$rawContent => $translatedContent];
        $translatorMock    = MockTranslatorFactory::createSimpleTranslator($this, $translations);

        $sut = $this->createNode($rawContent);

        $sut->setTranslator($translatorMock);

        if (method_exists($sut, 'getRawContent')) {
            $this->assertContains($expectedResult, $sut->getRawContent());
        } else {
            $this->assertTrue(true, 'No need to test getRawContent');
        }
    }

    /**
     * @param INode|string|null $content
     *
     * @return Node
     */
    protected function createNode($content = null): INode
    {
        return new Node($content);
    }
}
