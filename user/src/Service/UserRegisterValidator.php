<?php

namespace App\Service;

use App\Entity\User;
use App\Model\UserDto;
use App\Resolver\UserDtoResolver;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserRegisterValidator
{

    private ConstraintViolationListInterface $errors;

    public function __construct(
        private ValidatorInterface $validator,
        private UserDtoResolver $userDtoResolver,
    )
    {}

    public function validate(UserDto $userDto): bool {
        $user = $this->userDtoResolver->resolveFromDto($userDto);

        $this->errors = $this->validator->validate($user);
        return count($this->errors) === 0;
    }

    public function getErrors(): ConstraintViolationListInterface
    {
        return $this->errors;
    }

}
