<?php

namespace App\Resolver;

use App\Entity\User;
use App\Model\UserDto;
use RuntimeException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class UserDtoResolver
{

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    )
    {}

    public function resolveFromDto(UserDto $userDto): User {
        if ($userDto->getId() !== null) {
            throw new RuntimeException('User dto cannot be resolved');
        }

        $user = new User();
        $user->setEmail($userDto->getEmail());
        $user->setFirstName($userDto->getFirstName());
        $user->setLastName($userDto->getLastName());

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $userDto->getPassword()
        );
        $user->setPassword($hashedPassword);

        return $user;
    }

}
