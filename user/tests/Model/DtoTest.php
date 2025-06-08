<?php

namespace App\Tests\Model;

use App\Entity\User;
use App\Model\UserDto;
use PHPUnit\Framework\TestCase;

class DtoTest extends TestCase
{
    public function testDtoCanBeCreatedSuccessfullyWithoutId(): void
    {
        $userDto = new UserDto(
            null,
            'email@example.com',
            'password',
            'first name',
            'last name',
        );

        $this->assertEquals(null, $userDto->getId());
        $this->assertEquals('email@example.com', $userDto->getEmail());
        $this->assertEquals('password', $userDto->getPassword());
        $this->assertEquals('first name', $userDto->getFirstName());
        $this->assertEquals('last name', $userDto->getLastName());
    }

    public function testDtoCanBeCreatedSuccessfullyWithId(): void
    {
        $userDto = new UserDto(
            21,
            'email@example.com',
            'password',
            'first name',
            'last name',
        );

        $this->assertEquals(21, $userDto->getId());
        $this->assertEquals('email@example.com', $userDto->getEmail());
        $this->assertEquals('password', $userDto->getPassword());
        $this->assertEquals('first name', $userDto->getFirstName());
        $this->assertEquals('last name', $userDto->getLastName());
    }

    public function testDtoCanBeCreatedFromEntity(): void
    {
        $user = new User();
        $user->setEmail('email@example.com');
        $user->setPassword('password');
        $user->setFirstName('first name');
        $user->setLastName('last name');

        $userDto = UserDto::fromEntity($user);
        $this->assertEquals(null, $userDto->getId());
        $this->assertEquals('email@example.com', $userDto->getEmail());
        $this->assertEquals('password', $userDto->getPassword());
        $this->assertEquals('first name', $userDto->getFirstName());
        $this->assertEquals('last name', $userDto->getLastName());
    }
}
