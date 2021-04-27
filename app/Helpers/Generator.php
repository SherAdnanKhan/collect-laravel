<?php

namespace App\Helpers;

class Generator
{
    /**
     * @param $nonce
     * @param int $ttl
     * @return string
     */
    public static function makePPLJWTWithKey($nonce, $ttl = 600): string
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

        $payload = json_encode([
            "iss" => "Veva",
            "iat" => time(),
            "exp" => time() + $ttl,
            "aud" => "Veva",
            "sub" => "Veva",
            "code" => "200",
            "jti" => $nonce
        ]);

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        $signature = hash_hmac('sha256', sprintf('%s.%s', $base64UrlHeader, $base64UrlPayload), trim(env('PPL_KEY')), true);

        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        return sprintf('%s.%s.%s', $base64UrlHeader, $base64UrlPayload, $base64UrlSignature);
    }
}
