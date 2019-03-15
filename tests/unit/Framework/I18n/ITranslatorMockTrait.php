<?php

declare(strict_types=1);

namespace AbterPhp\Framework\I18n;

use PHPUnit\Framework\MockObject\MockObject;

trait ITranslatorMockTrait
{
    /**
     * @param array|null $translations
     *
     * @return ITranslator|MockObject|null
     */
    protected function getTranslatorMock(array $translations = null): ?ITranslator
    {
        if ($translations) {
            return null;
        }

        if (!($this instanceof \PHPUnit\Framework\TestCase)) {
            return null;
        }

        /** @var ITranslator|MockObject $translatorMock */
        $translatorMock = $this->getMockBuilder(ITranslator::class)
            ->setMethods(['translate', 'canTranslate'])
            ->getMock();

        $translatorMock
            ->expects($this->any())
            ->method('translate')
            ->willReturnCallback(
                function ($key) use ($translations) {
                    if ($translations && array_key_exists($key, $translations)) {
                        return $translations[$key];
                    }

                    return 'not found';
                }
            );

        $translatorMock
            ->expects($this->any())
            ->method('canTranslate')
            ->willReturnCallback(
                function ($key) use ($translations) {
                    if ($translations && array_key_exists($key, $translations)) {
                        return true;
                    }

                    return false;
                }
            );

        return $translatorMock;
    }
}
