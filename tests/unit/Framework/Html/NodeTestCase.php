<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html;

use AbterPhp\Framework\I18n\MockTranslatorFactory;
use PHPUnit\Framework\TestCase;

abstract class NodeTestCase extends TestCase
{
    /**
     * @return array
     */
    public function setContentFailureProvider(): array
    {
        return [
            [new \stdClass()],
        ];
    }

    /**
     * @dataProvider setContentFailureProvider
     *
     * @expectedException \InvalidArgumentException
     */
    public function testCreateFailure($content)
    {
        $this->createNode($content);
    }

    /**
     * @dataProvider setContentFailureProvider
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetContentFailure($content)
    {
        $sut = $this->createNode();

        $sut->setContent($content);
    }

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
     * @return array
     */
    abstract public function isMatchProvider(): array;

    /**
     * @dataProvider isMatchProvider
     *
     * @param string|null $className
     * @param string[]    $intents
     * @param int|null    $expectedResult
     */
    public function testIsMatch(?string $className, array $intents, bool $expectedResult)
    {
        $sut = $this->createNode();
        $sut->setIntent('foo', 'bar');

        $actualResult = $sut->isMatch($className, ...$intents);

        $this->assertSame($expectedResult, $actualResult);
    }

    public function testAddIntent()
    {
        $intent0 = 'foo';
        $intent1 = 'bar';

        $sut = $this->createNode();

        $sut->addIntent($intent0);
        $sut->addIntent($intent1);

        $intents = $sut->getIntents();

        $this->assertSame([$intent0, $intent1], $intents);
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
