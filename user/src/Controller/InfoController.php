<?php

namespace App\Controller;

use App\Model\UserDto;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class InfoController extends AbstractController
{
    #[Route('/api/users', name: 'get_all_users', methods: ['GET'], format: 'json')]
    #[IsGranted('ROLE_USER')]
    public function getAllUsers(Request $request, UserRepository $userRepository): JsonResponse
    {
        $offset = max(0, $request->query->getInt('offset', 0));

        $userPaginator = $userRepository->getPaginatedUsers($offset);

        // Convert the paginator result into an array of dtos
        $users = [];
        foreach ($userPaginator as $user) {
            $users[] = UserDto::fromEntity($user);
        }

        return new JsonResponse([
            'users' => $users,
            'previous' => $offset ? $offset - UserRepository::USERS_PER_PAGE : 0,
            'next' => min(count($userPaginator), $offset + UserRepository::USERS_PER_PAGE),
        ]);
    }
}
