<?php

declare(strict_types=1);

namespace Doctrine\Persistence;

use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\Mapping\ClassMetadataFactory;

/**
 * Contract for a Doctrine persistence layer ObjectManager class to implement.
 */
interface ObjectManager
{
    /**
     * Finds an object by its identifier.
     *
     * This is just a convenient shortcut for getRepository($className)->find($id).
     *
     * @param string $className The class name of the object to find.
     * @param mixed  $id        The identity of the object to find.
     *
     * @return object|null The found object.
     *
     * @template T
     * @psalm-param class-string<T> $className
     * @psalm-return T|null
     */
    public function find(string $className, $id) : ?object;

    /**
     * Tells the ObjectManager to make an instance managed and persistent.
     *
     * The object will be entered into the database as a result of the flush operation.
     *
     * NOTE: The persist operation always considers objects that are not yet known to
     * this ObjectManager as NEW. Do not pass detached objects to the persist operation.
     *
     * @param object $object The instance to make managed and persistent.
     */
    public function persist(object $object) : void;

    /**
     * Removes an object instance.
     *
     * A removed object will be removed from the database as a result of the flush operation.
     *
     * @param object $object The object instance to remove.
     */
    public function remove(object $object) : void;

    /**
     * Merges the state of a detached object into the persistence context
     * of this ObjectManager and returns the managed copy of the object.
     * The object passed to merge will not become associated/managed with this ObjectManager.
     *
     * @deprecated Merge operation is deprecated and will be removed in Persistence 2.0.
     *             Merging should be part of the business domain of an application rather than
     *             a generic operation of ObjectManager.
     */
    public function merge(object $object) : object;

    /**
     * Clears the ObjectManager. All objects that are currently managed
     * by this ObjectManager become detached.
     *
     * @param string|null $objectName if given, only objects of this type will get detached.
     */
    public function clear(?string $objectName = null) : void;

    /**
     * Detaches an object from the ObjectManager, causing a managed object to
     * become detached. Unflushed changes made to the object if any
     * (including removal of the object), will not be synchronized to the database.
     * Objects which previously referenced the detached object will continue to
     * reference it.
     *
     * @deprecated Detach operation is deprecated and will be removed in Persistence 2.0. Please use
     *             {@see ObjectManager::clear()} instead.
     *
     * @param object $object The object to detach.
     */
    public function detach(object $object) : void;

    /**
     * Refreshes the persistent state of an object from the database,
     * overriding any local changes that have not yet been persisted.
     *
     * @param object $object The object to refresh.
     */
    public function refresh(object $object) : void;

    /**
     * Flushes all changes to objects that have been queued up to now to the database.
     * This effectively synchronizes the in-memory state of managed objects with the
     * database.
     */
    public function flush() : void;

    /**
     * Gets the repository for a class.
     *
     * @template T
     * @psalm-param class-string<T> $className
     * @psalm-return ObjectRepository<T>
     */
    public function getRepository(string $className) : ObjectRepository;

    /**
     * Returns the ClassMetadata descriptor for a class.
     *
     * The class name must be the fully-qualified class name without a leading backslash
     * (as it is returned by get_class($obj)).
     */
    public function getClassMetadata(string $className) : ClassMetadata;

    /**
     * Gets the metadata factory used to gather the metadata of classes.
     */
    public function getMetadataFactory() : ClassMetadataFactory;

    /**
     * Helper method to initialize a lazy loading proxy or persistent collection.
     *
     * This method is a no-op for other objects.
     */
    public function initializeObject(object $obj) : void;

    /**
     * Checks if the object is part of the current UnitOfWork and therefore managed.
     */
    public function contains(object $object) : bool;
}
