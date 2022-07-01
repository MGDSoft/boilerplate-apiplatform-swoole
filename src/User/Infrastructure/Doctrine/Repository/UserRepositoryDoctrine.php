<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Doctrine\Repository;

use App\Shared\Infrastructure\Persistence\Doctrine\AbstractRepositoryDoctrine;
use App\User\Domain\Model\User;
use App\User\Domain\Repository\UserRepositoryInterface;

final class UserRepositoryDoctrine extends AbstractRepositoryDoctrine implements UserRepositoryInterface
{
    protected const ENTITY_CLASS = User::class;
}
