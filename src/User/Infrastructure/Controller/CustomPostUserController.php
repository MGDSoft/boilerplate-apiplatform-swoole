<?php

namespace App\User\Infrastructure\Controller;

use App\Shared\Infrastructure\Api\Request\RequestProcessor;
use App\User\Application\Command\CustomUserCommand;
use App\User\Domain\Model\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsController]
class CustomPostUserController extends AbstractController
{
    public function __construct(
        protected readonly RequestProcessor $requestProcessor,
        protected readonly MessageBusInterface $bus,
    ) {
    }

    /**
     * @return mixed[]
     */
    public function __invoke(Request $request): array
    {
        $resources = $this->requestProcessor->process(User::class.'[]', CustomUserCommand::class);

        return $resources;
    }
}
