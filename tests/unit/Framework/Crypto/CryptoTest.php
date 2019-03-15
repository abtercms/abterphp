<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Crypto;

use Opulence\Cryptography\Encryption\IEncrypter;
use Opulence\Cryptography\Hashing\IHasher;
use PHPUnit\Framework\TestCase;

class CryptoTest extends TestCase
{
    /** @var Crypto */
    protected $sut;

    /** @var IEncrypter */
    protected $encrypter;

    /** @var IHasher */
    protected $hasher;

    /** @var string */
    protected $pepper = 'foo-bar-123-!?*';

    /** @var array */
    protected $hashOptions = [];

    /** @var string */
    protected $salt = '*?!-321-rab-oof';

    public function setUp()
    {
        $this->encrypter = $this->getMockBuilder(IEncrypter::class)
            ->setMethods(['encrypt', 'decrypt', 'setSecret'])
            ->getMock();

        $this->hasher = $this->getMockBuilder(IHasher::class)
            ->setMethods(['hash', 'verify', 'needsRehash'])
            ->getMock();

        $this->sut = new Crypto($this->encrypter, $this->hasher, $this->pepper, $this->hashOptions, $this->salt);
    }

    public function testVerifySecret()
    {
        $this->markTestIncomplete();
    }

    public function testPrepareSecret()
    {
        $this->markTestIncomplete();
    }

    public function testHashCrypt()
    {
        $this->markTestIncomplete();
    }
}
