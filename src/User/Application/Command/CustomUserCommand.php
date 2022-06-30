<?php

declare(strict_types=1);

namespace App\User\Application\Command;

use App\User\Domain\Model\User;

class CustomUserCommand
{
    /**
     * @param User[] $users
     */
    public function __construct(
         public array $users
     ) {
    }
}
