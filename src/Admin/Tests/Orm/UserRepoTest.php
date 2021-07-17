<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Tests\Orm;

use AbterPhp\Admin\Domain\Entities\User as Entity;
use AbterPhp\Admin\Domain\Entities\UserLanguage;
use AbterPhp\Admin\Orm\UserRepo;
use AbterPhp\Admin\Tests\TestCase\Orm\GridRepoTestCase;
use Opulence\Orm\DataMappers\IDataMapper;
use Opulence\Orm\IUnitOfWork;
use PHPUnit\Framework\MockObject\MockObject;

class UserRepoTest extends GridRepoTestCase
{
    /** @var UserRepo - System Under Test */
    protected UserRepo $sut;

    protected string $className = 'Foo';

    /** @var IDataMapper|MockObject */
    protected $dataMapperMock;

    /** @var IUnitOfWork|MockObject */
    protected $unitOfWorkMock;

    protected UserLanguage $userLanguageStub;

    public function setUp(): void
    {
        parent::setUp();

        $this->userLanguageStub = new UserLanguage('', '', '');

        $this->sut = new UserRepo($this->writerMock, $this->queryBuilder);
    }

    /**
     * @return array
     */
    protected function getStubRows(): array
    {
        $rows   = [];
        $rows[] = [
            'id'                       => 'foo',
            'username'                 => 'foo-username',
            'email'                    => 'foo-email',
            'password'                 => 'foo-password',
            'can_login'                => '1',
            'is_gravatar_allowed'      => '1',
            'user_language_id'         => 'foo-user_language_id',
            'user_language_identifier' => 'foo-user_language_identifier',
        ];
        $rows[] = [
            'id'                       => 'bar',
            'username'                 => 'bar-username',
            'email'                    => 'bar-email',
            'password'                 => 'bar-password',
            'can_login'                => '0',
            'is_gravatar_allowed'      => '0',
            'user_language_id'         => 'bar-user_language_id',
            'user_language_identifier' => 'bar-user_language_identifier',
        ];

        return $rows;
    }

    /**
     * @param int $i
     *
     * @return Entity
     */
    protected function createEntityStub(int $i = 0): Entity
    {
        $rows = $this->getStubRows();
        $row  = $rows[$i];

        $language = new UserLanguage('', '', '');

        return new Entity(
            $row['id'],
            $row['username'],
            $row['email'],
            $row['password'],
            (bool)$row['can_login'],
            (bool)$row['is_gravatar_allowed'],
            $language
        );
    }

    public function testGetByClientId()
    {
        $this->markTestIncomplete();
//        $identifier = 'foo-0';
//        $entityStub = new Entity('foo0', $identifier, '', '', false, false, $this->userLanguageStub);
//
//        $entityRegistry = $this->createEntityRegistryStub(null);
//
//        $this->dataMapperMock->expects($this->once())->method('getByClientId')->willReturn($entityStub);
//
//        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);
//
//        $actualResult = $this->sut->getByClientId($identifier);
//
//        $this->assertSame($entityStub, $actualResult);
    }

    public function testGetByUsername()
    {
        $this->markTestIncomplete();
//        $identifier = 'foo-0';
//        $entityStub = new Entity('foo0', $identifier, '', '', false, false, $this->userLanguageStub);
//
//        $entityRegistry = $this->createEntityRegistryStub(null);
//
//        $this->dataMapperMock->expects($this->once())->method('getByUsername')->willReturn($entityStub);
//
//        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);
//
//        $actualResult = $this->sut->getByUsername($identifier);
//
//        $this->assertSame($entityStub, $actualResult);
    }

    public function testGetByEmail()
    {
        $this->markTestIncomplete();
//        $identifier = 'foo-0';
//        $entityStub = new Entity('foo0', $identifier, '', '', false, false, $this->userLanguageStub);
//
//        $entityRegistry = $this->createEntityRegistryStub(null);
//
//        $this->dataMapperMock->expects($this->once())->method('getByEmail')->willReturn($entityStub);
//
//        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);
//
//        $actualResult = $this->sut->getByEmail($identifier);
//
//        $this->assertSame($entityStub, $actualResult);
    }

    public function testFind()
    {
        $this->markTestIncomplete();
//        $identifier = 'foo-0';
//        $entityStub = new Entity('foo0', $identifier, '', '', false, false, $this->userLanguageStub);
//
//        $entityRegistry = $this->createEntityRegistryStub(null);
//
//        $this->dataMapperMock->expects($this->once())->method('find')->willReturn($entityStub);
//
//        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);
//
//        $actualResult = $this->sut->find($identifier);
//
//        $this->assertSame($entityStub, $actualResult);
    }
}
