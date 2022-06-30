<?php

declare(strict_types=1);

namespace App\User\Domain\Model;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\User\Domain\Model\Enum\UserStatus;
use App\User\Infrastructure\Controller\CustomPostUserController;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource(
    collectionOperations: [
        'get',
        'post',
        'user_custom' => ['path' => '/user-custom', 'method' => 'PUT', 'controller' => CustomPostUserController::class, 'deserialize' => false],
    ],
    itemOperations: ['get'],
    denormalizationContext: ['groups' => ['write']],
    normalizationContext: ['groups' => ['read']]
)]
class User
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'integer')]
        #[ORM\GeneratedValue('AUTO')]
        #[ApiProperty(identifier: true)]
        #[Groups(['read'])]
        public ?int $id = null,

        #[ORM\Column(type: 'string')]
        #[Assert\Length(null, 10, groups: ['Default'])]
        #[Groups(['read', 'write'])]
        public ?string $name = null,

        #[ORM\Column(type: 'string', enumType: UserStatus::class)]
        public UserStatus $status = UserStatus::ENABLED,
    ) {
    }
}
