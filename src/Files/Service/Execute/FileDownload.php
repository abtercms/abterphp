<?php

declare(strict_types=1);

namespace AbterPhp\Files\Service\Execute;

use AbterPhp\Framework\Service\Execute\RepoServiceAbstract;
use AbterPhp\Files\Validation\Factory\FileDownload as ValidatorFactory;
use Cocur\Slugify\Slugify;
use AbterPhp\Files\Domain\Entities\File;
use AbterPhp\Files\Domain\Entities\FileDownload as Entity;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Admin\Domain\Entities\User;
use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Admin\Domain\Entities\UserLanguage;
use AbterPhp\Files\Orm\FileDownloadRepo as GridRepo;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Orm\IUnitOfWork;

class FileDownload extends RepoServiceAbstract
{
    /** @var Slugify */
    protected $slugify;

    /**
     * FileDownload constructor.
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
        $file         = new File(0, '', '', '');
        $userGroup    = new UserGroup(0, '', '', []);
        $userLanguage = new UserLanguage(0, '', '');
        $user         = new User(0, '', '', '', $userGroup, $userLanguage);

        return new Entity(0, $file, $user, new \DateTime());
    }

    /**
     * @param Entity $entity
     * @param array  $data
     *
     * @return Entity
     */
    protected function fillEntity(IStringerEntity $entity, array $data): IStringerEntity
    {
        return $entity;
    }
}
