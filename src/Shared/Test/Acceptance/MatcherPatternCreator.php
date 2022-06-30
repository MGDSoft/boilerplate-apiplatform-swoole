<?php

declare(strict_types=1);

namespace App\Shared\Test\Acceptance;

class MatcherPatternCreator
{
    public static function violation(string $code): string
    {
        return <<<JSON
        {
            "violations": [
                { 
                    "code": "$code",
                    "@*@": "@*@"
                },
                "@...@"
            ],
            "@*@": "@*@"
        }
        JSON;
    }

    /**
     * @param string[] $codes
     */
    public static function violations(array $codes): \Iterator
    {
        foreach ($codes as $code) {
            yield static::violation($code);
        }
    }

    public static function description(string $description): string
    {
        return <<<JSON
        {
            "hydra:description": "$description",
            "@*@": "@*@"
        }
        JSON;
    }
}
