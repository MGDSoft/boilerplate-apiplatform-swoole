<?php

declare(strict_types=1);

namespace App\Shared\Domain\Repository;

interface RepositoryInterface
{
    /**
     * Not used type hint BC.
     *
     * @return object|null
     * @phpstan-ignore-next-line
     */
    public function find($id);

    /**
     * Not used type hint BC.
     *
     * @return mixed[]
     * @phpstan-ignore-next-line
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null);

    /**
     * Not used type hint BC.
     *
     * @return mixed[]
     */
    public function findAll();

    public function count(): int;

    public function save(mixed $model): void;

    /**
     * @param mixed[] $models
     */
    public function saveCollection(array $models): void;

    public function removeAll(): int;
}
