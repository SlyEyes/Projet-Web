<?php

namespace Linkedout\App\services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Service for the JWT. It can generate and decode a token.
 * @package Linkedout\App\services
 */
class JwtService
{
    private string $encodeKey;
    private Key $decodeKey;

    public function __construct()
    {
        $this->encodeKey = $_ENV['JWT_SECRET'];
        $this->decodeKey = new Key($this->encodeKey, 'HS256');
    }

    /**
     * Generate a JWT token from the given payload.
     * @param array $payload The payload to encode. There is no processing done on the payload, so it must contain all
     * the required data.
     * @return string The generated token
     */
    public function generateToken(array $payload): string
    {
        return JWT::encode($payload, $this->encodeKey, 'HS256');
    }

    /**
     * Decode a JWT token and return the payload.
     * @param string $token The token to decode
     * @return array The raw decoded payload, as an associative array
     */
    public function decodeToken(string $token): array
    {
        return (array)JWT::decode($token, $this->decodeKey);
    }
}
