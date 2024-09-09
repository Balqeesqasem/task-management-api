<?php

namespace App\Libraries;

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTAuth
{
    public static function authenticate($request)
    {
        $key = getenv('JWT_SECRET_KEY') ?: 'default-secret-key';
        $authHeader = $request->getServer('HTTP_AUTHORIZATION');

        if (!$authHeader) {
            return ['status' => 401, 'message' => 'Unauthorized'];
        }

        list($token) = sscanf($authHeader, 'Bearer %s');

        if (!$token) {
            return ['status' => 401, 'message' => 'Invalid token'];
        }

        try {
            
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            return ['status' => 200, 'user' => $decoded->sub];
        } catch (\Exception $e) {
            return ['status' => 401, 'message' => 'Token invalid: ' . $e->getMessage()];
        }
    }
}
