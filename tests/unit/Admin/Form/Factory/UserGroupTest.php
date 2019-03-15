<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Form\Factory;

use AbterPhp\Admin\Domain\Entities\AdminResource;
use AbterPhp\Admin\Domain\Entities\UserGroup as Entity;
use AbterPhp\Admin\Orm\AdminResourceRepo;
use AbterPhp\Framework\I18n\ITranslator;
use Opulence\Http\Requests\RequestMethods;
use Opulence\Sessions\ISession;
use Opulence\Sessions\Session;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserGroupTest extends TestCase
{
    /** @var ISession|MockObject */
    protected $sessionMock;

    /** @var ITranslator|MockObject */
    protected $translatorMock;

    /** @var AdminResourceRepo|MockObject */
    protected $adminResourceRepoMock;

    /** @var UserGroup */
    protected $sut;

    public function setUp()
    {
        $this->sessionMock = $this->getMockBuilder(Session::class)
            ->setMethods(['get'])
            ->getMock();
        $this->sessionMock->expects($this->any())->method('get')->willReturnArgument(0);

        $this->translatorMock = $this->getMockBuilder(ITranslator::class)
            ->setMethods(['translate', 'canTranslate'])
            ->getMock();
        $this->translatorMock->expects($this->any())->method('translate')->willReturnArgument(0);

        $this->adminResourceRepoMock = $this->getMockBuilder(AdminResourceRepo::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAll'])
            ->getMock();

        $this->sut = new UserGroup($this->sessionMock, $this->translatorMock, $this->adminResourceRepoMock);
    }

    public function testCreate()
    {
        $action            = 'foo';
        $method            = RequestMethods::POST;
        $showUrl           = 'bar';
        $entityId          = 36;
        $identifier        = 'blah';
        $name              = 'zorros';
        $allAdminResources = [
            new AdminResource(21, 'ar-21'),
            new AdminResource(47, 'ar-47'),
            new AdminResource(64, 'ar-64'),
            new AdminResource(187, 'ar-187'),
        ];
        $adminResources    = [$allAdminResources[1], $allAdminResources[3]];

        $this->adminResourceRepoMock
            ->expects($this->any())
            ->method('getAll')
            ->willReturn($allAdminResources);

        $entityMock = $this->createMockEntity();

        $entityMock->expects($this->any())->method('getId')->willReturn($entityId);
        $entityMock->expects($this->any())->method('getIdentifier')->willReturn($identifier);
        $entityMock->expects($this->any())->method('getName')->willReturn($name);
        $entityMock->expects($this->any())->method('getAdminResources')->willReturn($adminResources);

        $form = (string)$this->sut->create($action, $method, $showUrl, $entityMock);

        $this->assertContains($action, $form);
        $this->assertContains($showUrl, $form);
        $this->assertContains('CSRF', $form);
        $this->assertContains('POST', $form);
        $this->assertContains('identifier', $form);
        $this->assertContains('name', $form);
        $this->assertContains('admin_resource_ids', $form);
        $this->assertContains('selected', $form);
        $this->assertContains('button', $form);
    }

    /**
     * @return MockObject|Entity
     */
    protected function createMockEntity()
    {
        $entityMock = $this->getMockBuilder(Entity::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'getId',
                    'getIdentifier',
                    'getName',
                    'getAdminResources',
                ]
            )
            ->getMock();

        return $entityMock;
    }
}
