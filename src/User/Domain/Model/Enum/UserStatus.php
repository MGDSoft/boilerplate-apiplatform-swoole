<?php

namespace App\User\Domain\Model\Enum;

enum UserStatus: string
{
    case ENABLED = 'ENABLED';
    case DISABLED = 'DISABLED';
}
