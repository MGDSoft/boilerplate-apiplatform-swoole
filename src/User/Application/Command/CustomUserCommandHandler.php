<?php

declare(strict_types=1);

namespace App\User\Application\Command;

use App\User\Domain\Model\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CustomUserCommandHandler
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
    ) {
    }

    /**
     * @return User[]
     */
    public function __invoke(CustomUserCommand $command): array
    {
        // do something
        $this->userRepository->saveCollection($command->users);

        return $this->userRepository->findAll();
    }
}
