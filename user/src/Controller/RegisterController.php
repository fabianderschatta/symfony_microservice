<?php

namespace App\Controller;

use App\Model\UserDto;
use App\Service\RegisterService;
use App\Service\UserRegisterValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    #[Route('/api/register', name: 'user_register', methods: ['POST'], format: 'json')]
    public function register(
        UserRegisterValidator $userRegisterValidator,
        RegisterService $registerService,
        #[MapRequestPayload] UserDto $userDto
    ): JsonResponse
    {
        $isValid = $userRegisterValidator->validate($userDto);
        if ($isValid !== true) {
            return $this->json(['error' => 'User already exists.'], Response::HTTP_BAD_REQUEST);
        }

        $user = $registerService->register($userDto);

        return $this->json(UserDto::fromEntity($user));
    }
}
