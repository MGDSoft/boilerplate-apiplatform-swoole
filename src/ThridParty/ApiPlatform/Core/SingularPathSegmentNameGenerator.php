<?php

declare(strict_types=1);

namespace App\ThridParty\ApiPlatform\Core;

use ApiPlatform\Core\Operation\PathSegmentNameGeneratorInterface;

final class SingularPathSegmentNameGenerator implements PathSegmentNameGeneratorInterface
{
    public function getSegmentName(string $name, bool $collection=true): string
    {
        return $this->dashize($name);
    }

    private function dashize(string $string): string
    {
        return strtolower(preg_replace('~(?<=\\w)([A-Z])~', '-$1', $string));
    }
}
