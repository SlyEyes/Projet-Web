<?php

namespace Linkedout\App\services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    private string $encodeKey;
    private Key $decodeKey;

    public function __construct()
    {
        $this->encodeKey = $_ENV['JWT_SECRET'];
        $this->decodeKey = new Key($this->encodeKey, 'HS256');
    }

    public function generateToken(array $payload): string
    {
        return JWT::encode($payload, $this->encodeKey, 'HS256');
    }

    public function decodeToken(string $token): array
    {
        return (array)JWT::decode($token, $this->decodeKey);
    }
}
