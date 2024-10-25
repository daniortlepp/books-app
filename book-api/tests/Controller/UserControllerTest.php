<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class UserControllerTest extends WebTestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
    }

    public function testUserRegistration(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();

        $data = [
            'email' => 'usertest@mail.com',
            'firstName' => 'Test',
            'lastName' => 'User',
            'password' => 'testpassword',
        ];
    
        $client->request('POST', '/user/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
    
        $this->assertResponseIsSuccessful();

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'usertest@mail.com']);
        $this->assertNotNull($user);
        $this->assertEquals('usertest@mail.com', $user->getEmail());
        $this->assertEquals('Test', $user->getFirstName());
        $this->assertEquals('User', $user->getLastName());
    }
}
