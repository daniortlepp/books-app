<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Book;

class BookControllerTest extends WebTestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
    }

    public function testListBooks()
    {
        self::ensureKernelShutdown();
        $client = static::createClient();

        $client->request('POST', '/user/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'test@user.com',
            'password' => 'testpassword',
        ]));

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $token = $data['token'];

        $client->request('GET', '/api/books', [], [], [
            'HTTP_Authorization' => 'Bearer ' . $token,
        ]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        $books = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount(1, $books);
        $this->assertEquals('Test Book', $books[0]['title']);
    }

    public function testCreateBook(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();

        $client->request('POST', '/user/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'test@user.com',
            'password' => 'testpassword',
        ]));

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $token = $data['token'];

        $data = [
            'title' => 'Teste de Livro',
            'author' => 'Autor de Teste',
            'numberOfPages' => 300,
            'description' => 'Descrição do livro de teste.',
        ];

        $client->request('POST', '/api/books', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_Authorization' => 'Bearer ' . $token,], json_encode($data));

        $this->assertResponseIsSuccessful();

        $book = $this->entityManager->getRepository(Book::class)->findOneBy(['title' => 'Teste de Livro']);
        
        $this->assertNotNull($book, 'Livro não foi criado no banco de dados.');
        $this->assertEquals('Teste de Livro', $book->getTitle(), 'O título do livro não está correto.');
        $this->assertEquals('Autor de Teste', $book->getAuthor(), 'O autor do livro não está correto.');
        $this->assertEquals(300, $book->getNumberOfPages(), 'O número de páginas do livro não está correto.');
        $this->assertEquals('Descrição do livro de teste.', $book->getDescription(), 'A descrição do livro não está correta.');
    }
}
