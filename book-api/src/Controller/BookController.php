<?php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\JWTTokenService;

class BookController extends AbstractController
{
    private $jwtTokenService;

    public function __construct(JWTTokenService $jwtTokenService)
    {
        $this->jwtTokenService = $jwtTokenService;
    }
    
    #[Route('/api/books', name: 'books', methods: ['GET'])]
    public function getBooks(EntityManagerInterface $em, Request $request): JsonResponse
    {
        $authHeader = $request->headers->get('Authorization');
        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return new JsonResponse(['error' => 'Token ausente ou inválido'], 401);
        }

        $token = $matches[1];
        $decoded = $this->jwtTokenService->validateToken($token);
        if (!$decoded) {
            return new JsonResponse(['error' => 'Token inválido ou expirado'], 401);
        }

        $books = $em->getRepository(Book::class)->findAll();
        return $this->json($books);
    }

    #[Route('/api/books', name: 'books_create', methods: ['POST'])]
    public function createBook(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $authHeader = $request->headers->get('Authorization');
        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return new JsonResponse(['error' => 'Token ausente ou inválido'], 401);
        }

        $token = $matches[1];
        $decoded = $this->jwtTokenService->validateToken($token);
        if (!$decoded) {
            return new JsonResponse(['error' => 'Token inválido ou expirado'], 401);
        }

        $data = json_decode($request->getContent(), true);

        $book = new Book();
        $book->setTitle($data['title']);
        $book->setDescription($data['description']);
        $book->setAuthor($data['author']);
        $book->setNumberOfPages($data['numberOfPages']);
        $book->createdAt(new \DateTime());

        $em->persist($book);
        $em->flush();

        return $this->json($book);
    }

    #[Route('/api/books/{id}', name: 'books_show', methods: ['GET'])]
    public function getBook($id, EntityManagerInterface $em, Request $request): JsonResponse
    {
        $authHeader = $request->headers->get('Authorization');
        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return new JsonResponse(['error' => 'Token ausente ou inválido'], 401);
        }

        $token = $matches[1];
        $decoded = $this->jwtTokenService->validateToken($token);
        if (!$decoded) {
            return new JsonResponse(['error' => 'Token inválido ou expirado'], 401);
        }

        $book = $em->getRepository(Book::class)->find($id);

        if (!$book) {
            return $this->json(['message' => 'Livro não encontrado'], 404);
        }

        return $this->json($book);
    }

    #[Route('/api/books/{id}', name: 'books_update', methods: ['PUT'])]
    public function updateBook($id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $authHeader = $request->headers->get('Authorization');
        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return new JsonResponse(['error' => 'Token ausente ou inválido'], 401);
        }

        $token = $matches[1];
        $decoded = $this->jwtTokenService->validateToken($token);
        if (!$decoded) {
            return new JsonResponse(['error' => 'Token inválido ou expirado'], 401);
        }

        $book = $em->getRepository(Book::class)->find($id);

        if (!$book) {
            return $this->json(['message' => 'Livro não encontrado'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $book->setTitle($data['title'] ?? $book->getTitle());
        $book->setDescription($data['description'] ?? $book->getDescription());
        $book->setAuthor($data['author'] ?? $book->getAuthor());
        $book->setNumberOfPages($data['numberOfPages'] ?? $book->getNumberOfPages());
        $book->setUpdatedAt(new \DateTime());

        $em->flush();

        return $this->json($book);
    }

    #[Route('/api/books/{id}', name: 'books_delete', methods: ['DELETE'])]
    public function deleteBook($id, EntityManagerInterface $em): JsonResponse
    {
        $book = $em->getRepository(Book::class)->find($id);

        if (!$book) {
            return $this->json(['message' => 'Livro não encontrado'], 404);
        }

        $em->remove($book);
        $em->flush();

        return $this->json(['message' => 'Livro excluído com sucesso!']);
    }
}
