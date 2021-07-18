<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Orm;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;

/**
 * Defines the interface for repositories to implement
 */
interface IRepository
{
    /**
     * Adds an entity to the repo
     *
     * @param IStringerEntity $entity The entity to add
     */
    public function add(IStringerEntity $entity);

    /**
     * Deletes an entity from the repo
     *
     * @param IStringerEntity $entity The entity to delete
     */
    public function delete(IStringerEntity $entity);

    /**
     * Gets all the entities
     *
     * @return IStringerEntity[] The list of all the entities of this type
     */
    public function getAll(): array;

    /**
     * Gets the entity with the input Id
     *
     * @param string $id The Id of the entity we're searching for
     *
     * @return IStringerEntity|null The entity with the input Id
     */
    public function getById(string $id): ?IStringerEntity;
}
