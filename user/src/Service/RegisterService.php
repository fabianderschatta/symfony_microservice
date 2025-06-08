<?php

namespace App\Service;

use App\Entity\User;
use App\Model\UserDto;
use App\Resolver\UserDtoResolver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterService
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private UserDtoResolver $userDtoResolver,
    )
    {}

    public function register(UserDto $userDto): User {
        $user = $this->userDtoResolver->resolveFromDto($userDto);
        $user->setRoles(['ROLE_USER']);

        $this->entityManager->persist($user);

        $this->entityManager->flush();

        return $user;
    }

}
