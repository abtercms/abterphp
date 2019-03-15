<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Session;

use Opulence\Sessions\ISession;
use PHPUnit\Framework\MockObject\MockObject;

trait ISessionMockTrait
{
    /**
     * @param array|null $sessionData
     * @param string     $name
     * @param string|int $id
     *
     * @return ISession|MockObject|null
     */
    protected function getSessionMock(array $sessionData = null, string $name = 'foo', $sessionId = 'bar'): ?ISession
    {
        if ($sessionData) {
            return null;
        }

        if (!($this instanceof \PHPUnit\Framework\TestCase)) {
            return null;
        }

        /** @var ISession|MockObject $sessionMock */
        $sessionMock = $this->getMockBuilder(ISession::class)
            ->setMethods(
                [
                    'ageFlashData',
                    'delete',
                    'flash',
                    'flush',
                    'get',
                    'getAll',
                    'getId',
                    'getName',
                    'has',
                    'hasStarted',
                    'reflash',
                    'regenerateId',
                    'set',
                    'setId',
                    'setMany',
                    'setName',
                    'start',
                    'offsetExists',
                    'offsetGet',
                    'offsetSet',
                    'offsetUnset',
                ]
            )
            ->getMock();

        $sessionMock
            ->expects($this->any())
            ->method('get')
            ->willReturnCallback(
                function ($key, $defaultValue = null) use ($sessionData) {
                    if (array_key_exists($key, $sessionData)) {
                        return $sessionData[$key];
                    }

                    return $defaultValue;
                }
            );

        $sessionMock
            ->expects($this->any())
            ->method('has')
            ->willReturnCallback(
                function ($key) use ($sessionData) {
                    if (array_key_exists($key, $sessionData)) {
                        return true;
                    }

                    return false;
                }
            );

        $sessionMock
            ->expects($this->any())
            ->method('hasStarted')
            ->willReturn(true);

        $sessionMock
            ->expects($this->any())
            ->method('getAll')
            ->willReturn($sessionData);

        $sessionMock
            ->expects($this->any())
            ->method('getId')
            ->willReturn($sessionId);

        $sessionMock
            ->expects($this->any())
            ->method('getName')
            ->willReturn($name);

        $sessionMock
            ->expects($this->any())
            ->method('start')
            ->willReturn(true);

        return $sessionMock;
    }
}
