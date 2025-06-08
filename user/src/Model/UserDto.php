<?php

namespace App\Model;

use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\PasswordStrength;

final readonly class UserDto implements \JsonSerializable
{

    public function __construct(
        private ?int   $id,
        #[Assert\NotBlank]
        #[Assert\Email]
        private string $email,
        #[Assert\NotBlank]
        #[Assert\PasswordStrength([
            'minScore' => PasswordStrength::STRENGTH_MEDIUM,
        ])]
        private string $password,
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        private string $first_name,
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        private string $last_name
    )
    {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }

    public static function fromEntity(User $entity): self {
        return new self(
            $entity->getId(),
            $entity->getEmail(),
            $entity->getPassword(),
            $entity->getFirstName(),
            $entity->getLastName()
        );
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
        ];
    }

}
