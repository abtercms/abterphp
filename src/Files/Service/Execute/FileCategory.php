<?php

declare(strict_types=1);

namespace AbterPhp\Files\Service\Execute;

use AbterPhp\Framework\Service\Execute\RepoServiceAbstract;
use AbterPhp\Files\Validation\Factory\FileCategory as ValidatorFactory;
use Cocur\Slugify\Slugify;
use AbterPhp\Files\Domain\Entities\FileCategory as Entity;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Files\Orm\FileCategoryRepo as GridRepo;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Orm\IUnitOfWork;

class FileCategory extends RepoServiceAbstract
{
    /** @var Slugify */
    protected $slugify;

    /**
     * FileCategory constructor.
     *
     * @param GridRepo         $repo
     * @param ValidatorFactory $validatorFactory
     * @param IUnitOfWork      $unitOfWork
     * @param IEventDispatcher $eventDispatcher
     * @param Slugify          $slugify
     */
    public function __construct(
        GridRepo $repo,
        ValidatorFactory $validatorFactory,
        IUnitOfWork $unitOfWork,
        IEventDispatcher $eventDispatcher,
        Slugify $slugify
    ) {
        parent::__construct($repo, $validatorFactory, $unitOfWork, $eventDispatcher);

        $this->slugify = $slugify;
    }

    /**
     * @param int|null $entityId
     *
     * @return Entity
     */
    public function createEntity(int $entityId = null): IStringerEntity
    {
        $entity = new Entity((int)$entityId, '', '', false);

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
        $name = isset($data['name']) ? (string)$data['name'] : '';

        $identifier = (string)$data['identifier'];
        if (empty($identifier)) {
            $identifier = $name;
        }
        $identifier = $this->slugify->slugify($identifier);

        $userGroups = [];
        if (array_key_exists('user_group_ids', $data)) {
            foreach ($data['user_group_ids'] as $id) {
                $userGroups[] = new UserGroup((int)$id, '', '');
            }
        }

        $isPublic = isset($data['is_public']) ? (bool)$data['is_public'] : false;

        $entity->setName($name)
            ->setIdentifier($identifier)
            ->setUserGroups($userGroups)
            ->setIsPublic($isPublic);

        return $entity;
    }
}
