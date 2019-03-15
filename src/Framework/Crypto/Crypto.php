<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Crypto;

use AbterPhp\Framework\Security\SecurityException;
use Opulence\Cryptography\Encryption\IEncrypter;
use Opulence\Cryptography\Hashing\IHasher;

class Crypto
{
    const SECRET_HASH_LENGTH = 128;

    /** @var IHasher */
    protected $hasher;

    /** @var IEncrypter */
    protected $encrypter;

    /** @var string */
    protected $pepper = '';

    /** @var array */
    protected $hashOptions = [];

    /** @var string */
    protected $salt = '';

    /**
     * Authenticator constructor.
     *
     * @param IEncrypter $encrypter
     * @param IHasher    $hasher
     * @param string     $pepper
     * @param array      $hashOptions
     * @param string     $salt
     */
    public function __construct(
        IEncrypter $encrypter,
        IHasher $hasher,
        string $pepper,
        array $hashOptions,
        string $salt
    ) {
        $this->hasher      = $hasher;
        $this->encrypter   = $encrypter;
        $this->pepper      = $pepper;
        $this->hashOptions = $hashOptions;
        $this->salt        = $salt;
    }

    /**
     * This method is used to "fake" frontend hashing. Use with care!
     *
     * @param string $rawText
     *
     * @return string
     */
    public function prepareSecret(string $rawText)
    {
        return hash('sha3-512', $this->salt . $rawText);
    }

    /**
     * @param string $secret SHA3-512 encoded secret in hexadecimal
     *
     * @return string secret hashed and encrypted
     * @throws \Exception
     */
    public function hashCrypt(string $secret): string
    {
        if (\mb_strlen($secret) !== static::SECRET_HASH_LENGTH) {
            throw new SecurityException('packed password must be a valid SHA3-512 hash');
        }

        $hashedSecret = $this->hasher->hash($secret, $this->hashOptions, $this->pepper);

        $hashCryptedSecret = $this->encrypter->encrypt($hashedSecret);

        return $hashCryptedSecret;
    }

    /**
     * @param string $secret SHA3-512 encoded secret in hexadecimal
     * @param string $storedSecret hashed and encrypted secret to compare $secret against
     *
     * @return bool
     */
    public function verifySecret(string $secret, string $storedSecret): bool
    {
        try {
            $hashedSecret = $this->encrypter->decrypt($storedSecret);
        } catch (\Exception $e) {
            $hashedSecret = '';
        }

        return $this->hasher->verify($hashedSecret, $secret, $this->pepper);
    }
}
