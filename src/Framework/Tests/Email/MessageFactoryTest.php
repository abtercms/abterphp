<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Tests\Email;

use AbterPhp\Framework\Email\MessageFactory;
use PHPUnit\Framework\TestCase;
use Swift_Message;

class MessageFactoryTest extends TestCase
{
    /** @var MessageFactory - System Under Test */
    protected MessageFactory $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new MessageFactory();
    }

    public function testCreate(): void
    {
        $subject = 'foo';

        $actualResult = $this->sut->create($subject);

        $this->assertInstanceOf(Swift_Message::class, $actualResult);
    }
}
