<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Form\Factory;

use AbterPhp\Admin\Domain\Entities\User as Entity;
use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Admin\Domain\Entities\UserLanguage;
use AbterPhp\Admin\Orm\UserGroupRepo;
use AbterPhp\Admin\Orm\UserLanguageRepo;
use AbterPhp\Framework\I18n\ITranslator;
use Opulence\Http\Requests\RequestMethods;
use Opulence\Sessions\ISession;
use Opulence\Sessions\Session;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /** @var ISession|MockObject */
    protected $sessionMock;

    /** @var ITranslator|MockObject */
    protected $translatorMock;

    /** @var UserGroupRepo|MockObject */
    protected $userGroupRepoMock;

    /** @var UserLanguageRepo|MockObject */
    protected $userLanguageRepoMock;

    /** @var User */
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

        $this->userLanguageRepoMock = $this->getMockBuilder(UserLanguageRepo::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAll'])
            ->getMock();

        $this->sut = new User(
            $this->sessionMock,
            $this->translatorMock,
            $this->userGroupRepoMock,
            $this->userLanguageRepoMock
        );
    }

    public function testCreate()
    {
        $action            = 'foo';
        $method            = RequestMethods::POST;
        $showUrl           = 'bar';
        $entityId          = 36;
        $username          = 'zorro79';
        $email             = 'zorro79@example.com';
        $canLogin          = true;
        $isGravatarAllowed = true;
        $allUserGroups     = [
            new UserGroup(22, 'ug-22', 'UG 22'),
            new UserGroup(73, 'ug-73', 'UG 73'),
            new UserGroup(112, 'ug-112', 'UG 112'),
            new UserGroup(432, 'ug-432', 'UG 432'),
        ];
        $userGroups        = [
            $allUserGroups[0],
            $allUserGroups[2],
        ];
        $allUserLanguages  = [
            new UserLanguage(52, 'ul-52', 'UL 52'),
            new UserLanguage(77, 'ul-77', 'UL 77'),
            new UserLanguage(93, 'ul-93', 'UL 93'),
            new UserLanguage(94, 'ul-94', 'UL 94'),
        ];
        $userLanguage      = $allUserLanguages[1];

        $this->userGroupRepoMock->expects($this->any())->method('getAll')->willReturn($allUserGroups);
        $this->userLanguageRepoMock->expects($this->any())->method('getAll')->willReturn($allUserLanguages);

        $entityMock = $this->createMockEntity();

        $entityMock->expects($this->any())->method('getId')->willReturn($entityId);
        $entityMock->expects($this->any())->method('getUsername')->willReturn($username);
        $entityMock->expects($this->any())->method('getEmail')->willReturn($email);
        $entityMock->expects($this->any())->method('canLogin')->willReturn($canLogin);
        $entityMock->expects($this->any())->method('isGravatarAllowed')->willReturn($isGravatarAllowed);
        $entityMock->expects($this->any())->method('getUserGroups')->willReturn($userGroups);
        $entityMock->expects($this->any())->method('getUserLanguage')->willReturn($userLanguage);

        $form = (string)$this->sut->create($action, $method, $showUrl, $entityMock);

        $this->assertContains($action, $form);
        $this->assertContains($showUrl, $form);
        $this->assertContains('CSRF', $form);
        $this->assertContains('POST', $form);
        $this->assertContains('username', $form);
        $this->assertContains('email', $form);
        $this->assertContains('password', $form);
        $this->assertContains('password_confirmed', $form);
        $this->assertContains('raw_password', $form);
        $this->assertContains('raw_password_confirmed', $form);
        $this->assertContains('can_login', $form);
        $this->assertContains('is_gravatar_allowed', $form);
        $this->assertContains('user_group_ids', $form);
        $this->assertContains('user_language_id', $form);
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
                    'getUsername',
                    'getEmail',
                    'canLogin',
                    'isGravatarAllowed',
                    'getUserGroups',
                    'getUserLanguage',
                ]
            )
            ->getMock();

        return $entityMock;
    }
}
