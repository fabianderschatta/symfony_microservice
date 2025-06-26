<?php

namespace Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class InfoControllerTest extends WebTestCase
{

    private KernelBrowser $client;
    private UserRepository $userRepository;
    private EntityManager $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->userRepository = self::getContainer()->get(UserRepository::class);
        $this->entityManager = self::getContainer()->get('doctrine')->getManager();
    }

    /**
     * @return void
     */
    public function testFindAllUsersPaginated(): void
    {

    }

}
