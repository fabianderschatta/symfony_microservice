<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class InfoController extends AbstractController
{
    #[Route('/api/users', name: 'get_all_users', methods: ['GET'], format: 'json')]
    public function getAllUsers(): JsonResponse
    {
        $users = [];

        return $this->json($users);
    }
}
