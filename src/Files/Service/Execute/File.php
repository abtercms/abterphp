<?php

declare(strict_types=1);

namespace AbterPhp\Files\Service\Execute;

use AbterPhp\Framework\Filesystem\Uploader\Uploader;
use AbterPhp\Framework\Service\Execute\RepoServiceAbstract;
use AbterPhp\Files\Validation\Factory\File as ValidatorFactory;
use Cocur\Slugify\Slugify;
use AbterPhp\Files\Domain\Entities\File as Entity;
use AbterPhp\Files\Domain\Entities\FileCategory;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Files\Orm\FileCategoryRepo;
use AbterPhp\Files\Orm\FileRepo as GridRepo;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Http\Requests\UploadedFile;
use Opulence\Orm\IUnitOfWork;
use Opulence\Orm\OrmException;

class File extends RepoServiceAbstract
{
    const INPUT_NAME_FILE = 'file';

    /** @var Slugify */
    protected $slugify;

    /** @var FileCategoryRepo */
    protected $fileCategoryRepo;

    /** @var Uploader */
    protected $uploader;

    /**
     * File constructor.
     *
     * @param GridRepo         $repo
     * @param ValidatorFactory $validatorFactory
     * @param IUnitOfWork      $unitOfWork
     * @param IEventDispatcher $eventDispatcher
     * @param Slugify          $slugify
     * @param FileCategoryRepo $fileCategoryRepo
     * @param Uploader         $uploader
     */
    public function __construct(
        GridRepo $repo,
        ValidatorFactory $validatorFactory,
        IUnitOfWork $unitOfWork,
        IEventDispatcher $eventDispatcher,
        Slugify $slugify,
        FileCategoryRepo $fileCategoryRepo,
        Uploader $uploader
    ) {
        parent::__construct($repo, $validatorFactory, $unitOfWork, $eventDispatcher);

        $this->slugify          = $slugify;
        $this->fileCategoryRepo = $fileCategoryRepo;
        $this->uploader         = $uploader;
    }

    /**
     * @param string[]       $postData
     * @param UploadedFile[] $fileData
     *
     * @return int
     * @throws OrmException
     */
    public function create(array $postData, array $fileData): int
    {
        $entity = $this->fillEntity($this->createEntity(), $postData);

        $this->uploadFile($entity, $fileData);

        $this->repo->add($entity);

        $this->commitCreate($entity);

        return (int)$entity->getId();
    }

    /**
     * @param int            $entityId
     * @param string[]       $postData
     * @param UploadedFile[] $fileData
     *
     * @return bool
     * @throws OrmException
     */
    public function update(int $entityId, array $postData, array $fileData): bool
    {
        /** @var Entity $entity */
        $entity = $this->retrieveEntity($entityId);

        $this->fillEntity($entity, $postData);

        if ($fileData) {
            $this->deleteFile($entity);
            $this->uploadFile($entity, $fileData);
        }

        $this->commitUpdate($entity);

        return true;
    }

    /**
     * @param int $entityId
     *
     * @return bool
     * @throws OrmException
     */
    public function delete(int $entityId): bool
    {
        /** @var Entity $entity */
        $entity = $this->retrieveEntity($entityId);

        $this->deleteFile($entity);

        $this->repo->delete($entity);

        $this->commitDelete($entity);

        return true;
    }

    /**
     * @param Entity $entity
     */
    public function deleteFile(Entity $entity)
    {
        $this->uploader->delete($entity->getFilesystemName());
    }

    /**
     * @param Entity         $entity
     * @param UploadedFile[] $fileData
     */
    public function uploadFile(Entity $entity, array $fileData)
    {
        $paths = $this->uploader->persist($fileData);

        if (!$paths) {
            return;
        }

        $entity->setFilesystemName($paths[static::INPUT_NAME_FILE]);
        $entity->setPublicName($fileData[static::INPUT_NAME_FILE]->getTempFilename());
    }

    /**
     * @param int|null $entityId
     *
     * @return Entity
     */
    protected function createEntity(int $entityId = null): IStringerEntity
    {
        $fileCategory = new FileCategory(0, '', '', false, []);

        return new Entity((int)$entityId, '', '', '', $fileCategory, null);
    }

    /**
     * @param Entity $entity
     * @param array  $data
     *
     * @return Entity
     * @throws OrmException
     */
    protected function fillEntity(IStringerEntity $entity, array $data): IStringerEntity
    {
        $description = (string)$data['description'];

        /** @var FileCategory $fileCategory */
        $fileCategory = $this->fileCategoryRepo->getById($data['category_id']);

        $entity
            ->setDescription($description)
            ->setCategory($fileCategory);

        if (array_key_exists('file', $data)) {
            $entity
                ->setFilesystemName((string)$data['file'])
                ->setPublicName((string)$data['filename']);
        }

        return $entity;
    }
}
