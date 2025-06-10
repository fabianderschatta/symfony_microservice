<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterControllerTest extends WebTestCase
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
        $this->passwordHasher = self::getContainer()->get(UserPasswordHasherInterface::class);
    }

    /**
     * @return void
     */
    public function testUserCanRegister(): void
    {
        $users = $this->userRepository->findAll();
        $this->assertEmpty($users);

        $content = [
            'email' => 'email@example.com',
            'first_name' => 'first name',
            'last_name' => 'last name',
            'password' => 'Password!23/567*',
        ];

        $this->client->request(
            'POST',
            '/api/register',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($content),
        );

        $this->assertResponseIsSuccessful();
        $response = $this->client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $responseContent = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('id', $responseContent);
        $this->assertArrayHasKey('email', $responseContent);
        $this->assertArrayHasKey('first_name', $responseContent);
        $this->assertArrayHasKey('last_name', $responseContent);
        $this->assertArrayNotHasKey('password', $responseContent);

        $this->assertGreaterThan(0, $responseContent['id']);
        $this->assertEquals('first name', $responseContent['first_name']);
        $this->assertEquals('last name', $responseContent['last_name']);
        $this->assertEquals('email@example.com', $responseContent['email']);

        $users = $this->userRepository->findAll();
        $this->assertCount(1, $users);

        $user = $users[0];
        $this->assertEquals($responseContent['id'], $user->getId());
        $this->assertEquals($responseContent['email'], $user->getEmail());
        $this->assertEquals($responseContent['first_name'], $user->getFirstName());
        $this->assertEquals($responseContent['last_name'], $user->getLastName());
    }

    /**
     * @return void
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testUserCannotRegisterTwice(): void
    {
        // Make sure we already have a user
        $this->createUser();
        $this->assertEquals(1, $this->userRepository->count());

        $content = [
            'email' => 'existing@example.com',
            'first_name' => 'first name',
            'last_name' => 'last name',
            'password' => 'Password!23/567*',
        ];

        $this->client->request(
            'POST',
            '/api/register',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($content),
        );

        $this->client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @return User
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function createUser(): User {
        $user = new User();
        $user->setEmail('existing@example.com');
        $user->setFirstName('first name');
        $user->setLastName('last name');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'Password!23/567*'));
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
