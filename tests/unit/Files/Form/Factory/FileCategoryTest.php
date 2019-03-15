<?php

declare(strict_types=1);

namespace AbterPhp\Files\Form\Factory;

use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Admin\Orm\UserGroupRepo;
use AbterPhp\Files\Domain\Entities\FileCategory as Entity;
use AbterPhp\Framework\I18n\ITranslator;
use Opulence\Http\Requests\RequestMethods;
use Opulence\Sessions\ISession;
use Opulence\Sessions\Session;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FileCategoryTest extends TestCase
{
    /** @var ISession|MockObject */
    protected $sessionMock;

    /** @var ITranslator|MockObject */
    protected $translatorMock;

    /** @var UserGroupRepo|MockObject */
    protected $userGroupRepoMock;

    /** @var FileCategory */
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

        $this->userGroupRepoMock = $this->getMockBuilder(UserGroupRepo::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAll'])
            ->getMock();

        $this->sut = new FileCategory($this->sessionMock, $this->translatorMock, $this->userGroupRepoMock);
    }

    public function testCreate()
    {
        $action        = 'foo';
        $method        = RequestMethods::POST;
        $showUrl       = 'bar';
        $entityId      = 36;
        $name          = 'Blah!';
        $identifier    = 'blah';
        $allUserGroups = [
            new UserGroup(22, 'ug-22', 'UG 22', [], []),
            new UserGroup(73, 'ug-73', 'UG 73', [], []),
            new UserGroup(112, 'ug-112', 'UG 112', [], []),
            new UserGroup(432, 'ug-432', 'UG 432', [], []),
        ];
        $userGroups    = [
            new UserGroup(73, 'ug-73', 'UG 73', [], []),
            new UserGroup(112, 'ug-112', 'UG 112', [], []),
        ];

        $this->userGroupRepoMock->expects($this->any())->method('getAll')->willReturn($allUserGroups);

        $entityMock = $this->createMockEntity();

        $entityMock->expects($this->any())->method('getId')->willReturn($entityId);
        $entityMock->expects($this->any())->method('getIdentifier')->willReturn($identifier);
        $entityMock->expects($this->any())->method('getName')->willReturn($name);
        $entityMock->expects($this->any())->method('getUserGroups')->willReturn($userGroups);

        $form = (string)$this->sut->create($action, $method, $showUrl, $entityMock);

        $this->assertContains($action, $form);
        $this->assertContains($showUrl, $form);
        $this->assertContains('identifier', $form);
        $this->assertContains('name', $form);
        $this->assertContains('CSRF', $form);
        $this->assertContains('POST', $form);
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
            ->setMethods(['getId', 'getIdentifier', 'getName', 'getUserGroups'])
            ->getMock();

        return $entityMock;
    }
}
