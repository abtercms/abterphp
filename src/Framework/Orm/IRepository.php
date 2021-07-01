<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Orm;

use Opulence\Orm\IEntity;

/**
 * Defines the interface for repositories to implement
 */
interface IRepository
{
    /**
     * Adds an entity to the repo
     *
     * @param IEntity $entity The entity to add
     */
    public function add(IEntity $entity);

    /**
     * Deletes an entity from the repo
     *
     * @param IEntity $entity The entity to delete
     */
    public function delete(IEntity $entity);

    /**
     * Gets all the entities
     *
     * @return IEntity[] The list of all the entities of this type
     */
    public function getAll(): array;

    /**
     * Gets the entity with the input Id
     *
     * @param int|string $id The Id of the entity we're searching for
     *
     * @return IEntity|null The entity with the input Id
     */
    public function getById(string $id): ?IEntity;
}
