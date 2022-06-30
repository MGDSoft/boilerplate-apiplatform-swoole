<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\Repository\RepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

abstract class AbstractRepositoryDoctrine extends ServiceEntityRepository implements RepositoryInterface
{
    protected const ENTITY_CLASS = '';

    public function __construct(ManagerRegistry $registry)
    {
        if (!static::ENTITY_CLASS) {
            throw new \CompileError('Entity and alias must be configured');
        }

        parent::__construct($registry, static::ENTITY_CLASS);
    }

    public function save(mixed $model): void
    {
        $this->_em->persist($model);
        $this->_em->flush();
    }

    public function saveCollection(array $models): void
    {
        foreach ($models as $model) {
            $this->_em->persist($model);
        }
        $this->_em->flush();
    }

    public function removeAll(): int
    {
        return $this->_em->createQuery('DELETE FROM '.static::ENTITY_CLASS)->execute();
    }

    public function count($criteria = []): int
    {
        return parent::count($criteria);
    }
}
