<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\UserModel;

class AuthController extends ResourceController
{
    protected $format = 'json';
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // Register a new user
    // /auth/register, POST
    public function register()
    {
        $data = $this->request->getJSON(true);

        if (empty($data)) {
            return $this->fail('No data received.');
        }

        if (!$this->validate($this->userModel->validationRules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        $userId = $this->userModel->insert($data);
        $user = $this->userModel->find($userId);

        return $this->respondCreated(['message' => 'User registered successfully']);
    }
    
    
    // Authenticate a user and return a JWT
    // /auth/login, POST
    public function login()
    {
        $input = $this->request->getJSON(true);
        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';
    
        // Validate login credentials
        if (!$this->userModel->validateLogin($email, $password)) {
            return $this->failUnauthorized('Invalid credentials');
        }
    
        // Fetch the user data
        $user = $this->userModel->where('email', $email)->first();
    
        // Generate JWT token
        $key = getenv('JWT_SECRET_KEY') ?: 'default-secret-key';
        $iat = time(); // Issued at time
        $exp = $iat + 3600; // Token expires in 1 hour
    
        $payload = [
            'iat' => $iat,   // Issued at
            'exp' => $exp,   // Expiration time
            'sub' => $user['id'], // Subject (user ID)
        ];
    
        // Encode the token
        $token = JWT::encode($payload, $key, 'HS256');
    
        // Return the token in the response
        return $this->respond([
            'token' => $token
        ]);
    }
    
}
