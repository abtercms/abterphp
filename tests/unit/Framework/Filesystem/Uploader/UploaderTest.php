<?php

namespace AbterPhp\Framework\Filesystem\Uploader;

use League\Flysystem\Filesystem;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UploaderTest extends TestCase
{
    /** @var Uploader */
    protected $sut;

    /** @var Filesystem|MockObject */
    protected $filesystemMock;

    /** @var string */
    protected $filemanagerPath = '/root/to/path';

    public function setUp()
    {
        $this->filesystemMock = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->setMethods(['has', 'read', 'delete', 'readStream', 'getSize'])
            ->getMock();

        $this->sut = new Uploader($this->filesystemMock, $this->filemanagerPath);
    }

    public function testGetContent()
    {
        $this->markTestIncomplete();
    }

    public function testGetErrors()
    {
        $this->markTestIncomplete();
    }

    public function testGetPath()
    {
        $this->markTestIncomplete();
    }

    public function testSetPathFactory()
    {
        $this->markTestIncomplete();
    }

    public function testGetSize()
    {
        $this->markTestIncomplete();
    }

    public function testPersist()
    {
        $this->markTestIncomplete();
    }

    public function testGetStream()
    {
        $this->markTestIncomplete();
    }

    public function testDelete()
    {
        $this->markTestIncomplete();
    }
}
