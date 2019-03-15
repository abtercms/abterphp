<?php

declare(strict_types=1);

namespace AbterPhp\Framework\I18n;

use AbterPhp\Framework\Session\ISessionMockTrait;
use Opulence\Sessions\ISession;
use PHPUnit\Framework\TestCase;

class TranslatorTest extends TestCase
{
    use ISessionMockTrait;

    /** @var Translator */
    protected $sut;

    /** @var ISession */
    protected $sessionMock;

    /** @var string */
    protected $translationsDir = 'foo';

    /** @var string */
    protected $defaultLang = 'en';

    public function setUp()
    {
        $this->sessionMock = $this->getSessionMock();

        $this->sut = new Translator($this->sessionMock, $this->translationsDir, $this->defaultLang);
    }

    public function testTranslateByArgs()
    {
        $this->markTestIncomplete();
    }

    public function testSetTranslations()
    {
        $this->markTestIncomplete();
    }

    public function testTranslate()
    {
        $this->markTestIncomplete();
    }

    public function testCanTranslate()
    {
        $this->markTestIncomplete();
    }
}
