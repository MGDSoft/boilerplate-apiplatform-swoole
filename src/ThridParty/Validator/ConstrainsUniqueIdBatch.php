<?php

declare(strict_types=1);

namespace App\ThridParty\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ConstrainsUniqueIdBatch extends Constraint
{
    public string $message="Id '{{id}}' is duplicated";

    public const ERROR_CODE='8607f892-f0a3-11ec-8ea0-0242ac120002';

    protected const ERROR_NAMES=[
        self::ERROR_CODE => 'NOT_BLANK_ERROR',
    ];
}
