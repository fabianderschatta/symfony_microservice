<?php

namespace App\Tests\Resolver;

use App\Entity\User;
use App\Model\UserDto;
use App\Repository\UserRepository;
use App\Resolver\UserDtoResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserDtoResolverTest extends TestCase
{

    public function testResolveUserEntityFromDtoWithoutId(): void
    {
        $userDto = new UserDto(
            null,
            'email@example.com',
            'password',
            'first name',
            'last name',
        );

        $passwordHasherStub = $this->createMock(UserPasswordHasherInterface::class);
        $passwordHasherStub->expects($this->once())
            ->method('hashPassword')
            ->willReturn('hashed_password');

        $userResolver = new UserDtoResolver($passwordHasherStub);
        $userEntity = $userResolver->resolveFromDto($userDto);

        $this->assertEquals('email@example.com', $userEntity->getEmail());
        $this->assertEquals('hashed_password', $userEntity->getPassword());
        $this->assertEquals('first name', $userEntity->getFirstName());
        $this->assertEquals('last name', $userEntity->getLastName());
        $this->assertEquals(null, $userEntity->getId());
    }

    public function testResolveUserEntityFromDtoWithId(): void
    {
        $userDto = new UserDto(
            123,
            'email@example.com',
            'password',
            'first name',
            'last name',
        );

        $passwordHasherStub = $this->createMock(UserPasswordHasherInterface::class);
        $passwordHasherStub->expects($this->never())
            ->method('hashPassword');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('User dto cannot be resolved');

        $userResolver = new UserDtoResolver($passwordHasherStub);
        $userResolver->resolveFromDto($userDto);
    }
}
