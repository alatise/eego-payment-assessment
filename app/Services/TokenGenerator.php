<?php

declare(strict_types=1);

namespace App\Services;

class TokenGenerator
{
    /**
     * Number of raw bytes to generate before encoding.
     * 32 bytes → 43 URL-safe base64 chars (256 bits of entropy).
     * Collision probability is negligible for any realistic dataset size.
     */
    private const BYTE_LENGTH = 32;

    /**
     * Generate a single cryptographically random, URL-safe token.
     */
    public function generate(): string
    {
        $bytes = random_bytes(self::BYTE_LENGTH);

        // Base64-URL: replace + → -, / → _, strip padding =
        return rtrim(strtr(base64_encode($bytes), '+/', '-_'), '=');
    }
}