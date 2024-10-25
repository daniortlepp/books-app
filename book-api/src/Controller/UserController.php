<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Services\JWTTokenService;

class UserController extends AbstractController
{
    private $jwtTokenService;

    public function __construct(JWTTokenService $jwtTokenService)
    {
        $this->jwtTokenService = $jwtTokenService;
    }

    #[Route('/user/login', name: 'user_login', methods: ['POST'])]
    public function login(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email']) || !isset($data['password'])) {
            return $this->json([
                'status' => 'error',
                'message' => 'Email and password are required.'
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = $entityManager->getRepository(User::class)
            ->findOneBy(['email' => $data['email']]);

        // Verifica se o usuário existe
        if (!$user) {
            return $this->json([
                'status' => 'error',
                'message' => 'Invalid credentials.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Verifica se a senha está correta
        if (!$passwordHasher->isPasswordValid($user, $data['password'])) {
            return $this->json([
                'status' => 'error',
                'message' => 'Invalid credentials.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $this->jwtTokenService->generateToken($user);

        return $this->json([
            'status' => 'success',
            'message' => 'Login realizado com sucesso!',
            'token' => $token,
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
            ]
        ], Response::HTTP_OK);
    }

    #[Route('/user/register', name: 'user_register', methods: ['POST'])]
    public function register(
        Request $request, 
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        ValidatorInterface $validator
    ): Response {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setEmail($data['email'] ?? null);
        $user->setFirstName($data['firstName'] ?? null);
        $user->setLastName($data['lastName'] ?? null);
        $user->setIsActive(true);
        $user->setRoles($data['roles'] ?? ['USER']);

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $data['password'] ?? ''
        );
        $user->setPassword($hashedPassword);

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json([
                'status' => 'error',
                'errors' => $errorMessages
            ], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'status' => 'success',
            'message' => 'Cadastro do usuário realizado com sucesso!',
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
                'registeredAt' => $user->getRegisteredAt()->format('Y-m-d H:i:s')
            ]
        ], Response::HTTP_CREATED);
    }
}
