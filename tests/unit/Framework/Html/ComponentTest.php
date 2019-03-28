<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Html;

class ComponentTest extends CollectionTest
{
    public function testToStringIsEmptyByDefault()
    {
        parent::testToStringIsEmptyByDefault();
    }

    /**
     * @return array
     */
    public function toStringReturnsRawContentByDefaultProvider(): array
    {
        return [
            'string'  => ['foo', '<span>foo</span>'],
            'INode'   => [new Node('foo'), '<span>foo</span>'],
            'INode[]' => [[new Node('foo')], '<span>foo</span>'],
        ];
    }

    /**
     * @dataProvider toStringReturnsRawContentByDefaultProvider
     *
     * @param string $rawContent
     * @param string $expectedResult
     */
    public function testToStringReturnsRawContentByDefault($rawContent, string $expectedResult)
    {
        parent::testToStringReturnsRawContentByDefault($rawContent, $expectedResult);
    }

    /**
     * @return array
     */
    public function toStringCanReturnTranslatedContentProvider(): array
    {
        $translations = ['foo' => 'bar'];

        return [
            'string'  => ['foo', $translations, '<span>bar</span>'],
            'INode'   => [new Node('foo'), $translations, '<span>bar</span>'],
            'INode[]' => [[new Node('foo')], $translations, '<span>bar</span>'],
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
        parent::testToStringCanReturnTranslatedContent($rawContent, $translations, $expectedResult);
    }

    public function testSetIntentsCanOverwriteExistingIntents()
    {
        $sut = $this->createNode();

        $sut->setIntent('foo');
        $sut->setIntent('bar', 'baz');

        $this->assertEquals(['bar', 'baz'], $sut->getIntents());
    }

    public function testAddIntentAppendsToExistingIntents()
    {
        $sut = $this->createNode();

        $sut->setIntent('foo');
        $sut->addIntent('bar', 'baz');

        $this->assertEquals(['foo', 'bar', 'baz'], $sut->getIntents());
    }

    public function testHasAttributeWithNonEmptyAttribute()
    {
        $sut = $this->createNode();

        $this->assertFalse($sut->hasAttribute('foo'));

        $sut->setAttribute('foo', 'bar');

        $this->assertTrue($sut->hasAttribute('foo'));
    }

    /**
     * @return array
     */
    public function getAttributeProvider(): array
    {
        return [
            [null, null],
            ['bar', 'bar'],
            [['bar'], 'bar'],
            ['foo bar', 'foo bar'],
            ['foo foo bar', 'foo bar'],
            [['foo', 'foo', 'bar', 'foo bar', 'bar'], 'foo bar'],
        ];
    }

    /**
     * @dataProvider getAttributeProvider
     *
     * @param             $value
     * @param string|null $expectedResult
     */
    public function testGetAttributes($value, ?string $expectedResult)
    {
        $key = 'foo';

        $sut = $this->createNode();

        $values = (array)$value;
        $sut->setAttribute($key, ...$values);

        $actualResult = $sut->getAttributes();

        $this->assertEquals([$key => $expectedResult], $actualResult);
    }

    public function testHasAttributeWithEmptyAttribute()
    {
        $sut = $this->createNode();

        $this->assertFalse($sut->hasAttribute('foo'));

        $sut->setAttribute('foo', null);

        $this->assertTrue($sut->hasAttribute('foo'));
    }

    public function testHasAttributeWithMissingAttributeSet()
    {
        $sut = $this->createNode();

        $this->assertFalse($sut->hasAttribute('foo'));

        $sut->setAttribute('foo');

        $this->assertTrue($sut->hasAttribute('foo'));
    }

    /**
     * @dataProvider getAttributeProvider
     *
     * @param             $value
     * @param string|null $expectedResult
     */
    public function testGetAttribute($value, ?string $expectedResult)
    {
        $key = 'foo';

        $sut = $this->createNode();

        $values = (array)$value;
        $sut->setAttribute($key, ...$values);

        $actualResult = $sut->getAttribute($key);

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @dataProvider getAttributeProvider
     * @expectedException \RuntimeException
     *
     * @param             $value
     * @param string|null $expectedResult
     */
    public function testUnsetAttribute($value, ?string $expectedResult)
    {
        $key = 'foo';

        $sut = $this->createNode();

        $values = (array)$value;
        $sut->setAttribute($key, ...$values);

        $actualResult = $sut->getAttribute($key);
        $this->assertEquals($expectedResult, $actualResult);

        $sut->unsetAttribute($key);

        $sut->getAttribute($key);
    }

    public function testSetAttributesOverridesExistingAttributesSet()
    {
        $originalAttributes = ['foo' => 'bar'];
        $newAttributes      = ['bar' => 'baz'];
        $expectedResult     = $newAttributes;

        $sut = $this->createNode();
        $sut->setAttributes($originalAttributes);

        $sut->setAttributes($newAttributes);

        $actualResult = $sut->getAttributes();
        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testAddAttributesOverridesExistingAttributesSet()
    {
        $originalAttributes = ['foo' => 'bar', 'bar' => 'foo'];
        $newAttributes      = ['bar' => 'baz'];
        $expectedResult     = ['foo' => 'bar', 'bar' => 'baz'];

        $sut = $this->createNode();
        $sut->setAttributes($originalAttributes);

        $sut->addAttributes($newAttributes);

        $actualResult = $sut->getAttributes();
        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testSetAttributeOverridesExistingAttributeSet()
    {
        $key                = 'bar';
        $originalAttributes = ['foo' => 'bar', 'bar' => 'foo'];
        $newAttributes      = ['bar' => 'baz'];
        $expectedResult     = ['foo' => 'bar', 'bar' => 'baz'];

        $sut = $this->createNode();
        $sut->setAttributes($originalAttributes);

        $sut->setAttribute($key, $newAttributes[$key]);

        $actualResult = $sut->getAttributes();
        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testAppendToAttributeKeepsExistingAttributeSet()
    {
        $key                = 'bar';
        $originalAttributes = ['foo' => 'bar', 'bar' => 'foo'];
        $newAttributes      = ['bar' => 'baz'];
        $expectedResult     = ['foo' => 'bar', 'bar' => 'foo baz'];

        $sut = $this->createNode();
        $sut->setAttributes($originalAttributes);

        $sut->appendToAttribute($key, $newAttributes[$key]);

        $actualResult = $sut->getAttributes();
        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testAppendToClassKeepsExistingAttributeSet()
    {
        $originalAttributes = ['foo' => 'bar', 'class' => 'foo'];
        $newClasses         = ['class1', 'class2'];
        $expectedResult     = ['foo' => 'bar', 'class' => 'foo class1 class2'];

        $sut = $this->createNode();
        $sut->setAttributes($originalAttributes);

        $sut->appendToClass(...$newClasses);

        $actualResult = $sut->getAttributes();
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return array
     */
    public function findProvider(): array
    {
        $node1 = new Node('1');
        $node2 = new Node('2');

        return [
            [[], $node1, null],
            [[$node2], $node1, null],
            [[$node1, $node2], $node1, 0],
            [[$node1, $node2], $node2, 1],
        ];
    }

    /**
     * @dataProvider findProvider
     *
     * @param INode[]  $content
     * @param INode    $nodeToFind
     * @param int|null $expectedResult
     */
    public function testFind(array $content, INode $nodeToFind, ?int $expectedResult)
    {
        $sut = $this->createNode($content);

        $actualResult = $sut->find($nodeToFind);

        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @return array
     */
    public function isMatchProvider(): array
    {
        return [
            'INode-no-intent'               => [INode::class, [], true],
            'INode-foo-intent'              => [INode::class, ['foo'], true],
            'INode-bar-intent'              => [INode::class, ['bar'], true],
            'INode-foo-and-bar-intent'      => [INode::class, ['foo', 'bar'], true],
            'IComponent-foo-intent'         => [IComponent::class, ['foo'], true],
            'Component-foo-intent'          => [Component::class, ['foo'], true],
            'fail-INode-baz-intent'         => [INode::class, ['baz'], false],
            'fail-INode-foo-and-baz-intent' => [INode::class, ['foo', 'baz'], false],
            'fail-Node-foo-intent'          => [Node::class, ['foo'], false],
        ];
    }

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

    /**
     * @return array
     */
    public function findFirstChildProvider(): array
    {
        $node0       = new Node('0');
        $component1  = (new Component('1'))->setIntent('foo');
        $component2  = (new Component('2'))->setIntent('bar');
        $component3  = (new Component('3'))->setIntent('foo', 'bar');
        $notFindable = new Collection((new Component('4'))->setIntent('foo', 'baz'));
        $content     = [$node0, $component1, $component2, $component3, $notFindable];

        return [
            'INode-no-intent'               => [$content, INode::class, [], $component1],
            'INode-foo-intent'              => [$content, INode::class, ['foo'], $component1],
            'INode-bar-intent'              => [$content, INode::class, ['bar'], $component2],
            'INode-foo-and-bar-intent'      => [$content, INode::class, ['foo', 'bar'], $component3],
            'IComponent-foo-intent'         => [$content, IComponent::class, ['foo'], $component1],
            'Component-foo-intent'          => [$content, Component::class, ['foo'], $component1],
            'fail-INode-baz-intent'         => [$content, INode::class, ['baz'], null],
            'fail-INode-foo-and-baz-intent' => [$content, INode::class, ['foo', 'baz'], null],
            'fail-Node-foo-intent'          => [$content, Node::class, ['foo'], null],
        ];
    }

    /**
     * @dataProvider findFirstChildProvider
     *
     * @param INode[]     $content
     * @param string|null $className
     * @param string[]    $intents
     * @param INode|null  $expectedResult
     */
    public function testFindFirstChild(array $content, ?string $className, array $intents, ?INode $expectedResult)
    {
        $sut = $this->createNode($content);

        $actualResult = $sut->findFirstChild($className, ...$intents);

        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @return array
     */
    public function collectProvider(): array
    {
        $node0   = new Node('0');
        $comp1   = (new Component('1'))->setIntent('foo');
        $comp2   = (new Component('2'))->setIntent('bar');
        $comp3   = (new Component('3'))->setIntent('foo', 'bar');
        $coll1   = new Collection([$comp1, $node0, $comp2]);
        $coll2   = new Collection([$comp3, $node0, $coll1, $comp1]);
        $content = [$comp1, $node0, $comp2, $coll2, $comp3];

        $level0Expected     = [$comp1, $comp2, $comp3];
        $level1Expected     = [$comp1, $comp2, $comp3, $comp1, $comp3];
        $defaultExpected    = [$comp1, $comp2, $comp3, $comp1, $comp2, $comp1, $comp3];
        $fooOnlyExpected    = [$comp1, $comp3, $comp1, $comp1, $comp3];
        $fooBarOnlyExpected = [$comp3, $comp3];

        return [
            '0-depth'       => [$content, null, 0, [], $level0Expected],
            '1-depth'       => [$content, null, 1, [], $level1Expected],
            'default'       => [$content, null, -1, [], $defaultExpected],
            'inode-only'    => [$content, INode::class, -1, [], $defaultExpected],
            'stdclass-only' => [$content, \stdClass::class, -1, [], []],
            'foo-only'      => [$content, null, -1, ['foo'], $fooOnlyExpected],
            'foo-bar-only'  => [$content, null, -1, ['foo', 'bar'], $fooBarOnlyExpected],
        ];
    }

    /**
     * @dataProvider collectProvider
     *
     * @param INode[]     $content
     * @param string|null $className
     * @param int         $depth
     * @param string[]    $intents
     * @param INode[]     $expectedResult
     */
    public function testCollect(array $content, ?string $className, int $depth, array $intents, array $expectedResult)
    {
        $sut = $this->createNode($content);

        $actualResult = $sut->collect($className, $intents, $depth);

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @param INode[]|INode|string|null $content
     *
     * @return Component
     */
    protected function createNode($content = null): INode
    {
        return new Component($content);
    }
}
