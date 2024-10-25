<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTTokenService
{
    private string $secretKey;

    public function __construct()
    {
        $this->secretKey = $_ENV['JWT_SECRET_KEY']; 
    }

    public function generateToken($user): string
    {
        $payload = [
            'iss' => 'crud-books',
            'iat' => time(),
            'exp' => time() + (60 * 60),
            'user_id' => $user->getId(),
            'email' => $user->getEmail(),
        ];

        return JWT::encode($payload, $this->secretKey, 'HS256');
    }

    public function validateToken(string $token)
    {
        try {
            return JWT::decode($token, new Key($this->secretKey, 'HS256'));
        } catch (\Exception $e) {
            return null;
        }
    }
}
