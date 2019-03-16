<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Service\Execute;

use AbterPhp\Framework\Crypto\Crypto;
use AbterPhp\Framework\Service\Execute\RepoServiceAbstract;
use AbterPhp\Admin\Validation\Factory\User as ValidatorFactory;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Admin\Domain\Entities\User as Entity;
use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Admin\Domain\Entities\UserLanguage;
use AbterPhp\Admin\Orm\UserRepo as GridRepo;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Orm\IUnitOfWork;

class User extends RepoServiceAbstract
{
    /** @var Crypto */
    private $crypto;

    /**
     * User constructor.
     *
     * @param GridRepo         $repo
     * @param ValidatorFactory $validatorFactory
     * @param IUnitOfWork      $unitOfWork
     * @param IEventDispatcher $eventDispatcher
     * @param Crypto           $crypto
     */
    public function __construct(
        GridRepo $repo,
        ValidatorFactory $validatorFactory,
        IUnitOfWork $unitOfWork,
        IEventDispatcher $eventDispatcher,
        Crypto $crypto
    ) {
        parent::__construct(
            $repo,
            $validatorFactory,
            $unitOfWork,
            $eventDispatcher
        );

        $this->crypto = $crypto;
    }

    /**
     * @param int|null $entityId
     *
     * @return Entity
     */
    public function createEntity(int $entityId = null): IStringerEntity
    {
        $userLanguage = new UserLanguage(
            0,
            '',
            ''
        );
        $entity       = new Entity(
            (int)$entityId,
            '',
            '',
            '',
            false,
            false,
            $userLanguage
        );

        return $entity;
    }

    /**
     * @param Entity $entity
     * @param array  $data
     *
     * @return Entity
     */
    protected function fillEntity(IStringerEntity $entity, array $data): IStringerEntity
    {
        if (!($entity instanceof Entity)) {
            return $entity;
        }

        $username          = (string)$data['username'];
        $email             = (string)$data['email'];
        $password          = (string)$data['password'];
        $isGravatarAllowed = isset($data['is_gravatar_allowed']) ? (bool)$data['is_gravatar_allowed'] : false;
        $canLogin          = isset($data['can_login']) ? (bool)$data['can_login'] : false;
        $userLanguage      = new UserLanguage(
            (int)$data['user_language_id'],
            '',
            ''
        );
        $userGroups = [];
        foreach ($data['user_group_ids'] as $userGroupId) {
            $userGroups[] = new UserGroup(
                (int)$userGroupId,
                '',
                ''
            );
        }

        $entity->setUsername($username)
            ->setEmail($email)
            ->setIsGravatarAllowed($isGravatarAllowed)
            ->setCanLogin($canLogin)
            ->setUserLanguage($userLanguage)
            ->setUserGroups($userGroups);

        if ($password) {
            $entity->setPassword($this->crypto->hashCrypt($password));
        }

        return $entity;
    }
}
