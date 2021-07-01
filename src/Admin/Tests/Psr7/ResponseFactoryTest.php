<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Tests\Psr7;

use AbterPhp\Admin\Psr7\ResponseFactory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Opulence\Http\Responses\ResponseHeaders;

class ResponseFactoryTest extends TestCase
{
    /** @var ResponseFactory - System Under Test */
    protected ResponseFactory $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new ResponseFactory();
    }

    public function testCreate()
    {
        $response = $this->sut->create();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(ResponseHeaders::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }
}
