<?php

declare(strict_types=1);

namespace App\DevOps\Infrastructure\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatusController
{
    #[Route(path: '/status')]
    public function __invoke(): Response
    {
        return new Response();
    }
}
