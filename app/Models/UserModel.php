<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'email', 'password'];
    protected $useTimestamps = true;

    // Validation rules
    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[20]',
        'email'    => 'required|valid_email|is_unique[users.email]',
        'password' => 'required|min_length[8]',
    ];

    protected $validationMessages = [
        'username' => [
            'required' => 'Username is required',
            'min_length' => 'Username must be at least 3 characters long',
            'max_length' => 'Username cannot exceed 20 characters'
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Email must be a valid email address',
            'is_unique' => 'Email is already registered'
        ],
        'password' => [
            'required' => 'Password is required',
            'min_length' => 'Password must be at least 8 characters long'
        ]
    ];

    // Custom validation rules for login
    public function validateLogin($email, $password)
    {
        $user = $this->where('email', $email)->first();
        if (!$user) {
            return false;
        }
        return password_verify($password, $user['password']);
    }
}
