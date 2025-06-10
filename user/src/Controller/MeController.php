<?php

namespace App\Controller;

use App\Model\UserDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MeController extends AbstractController
{
    #[Route('/api/user/me', name: 'get_current_user', methods: ['GET'], format: 'json')]
    #[IsGranted('ROLE_USER')]
    public function getAllUsers(): JsonResponse
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        return $this->json(UserDto::fromEntity($user));
    }
}
