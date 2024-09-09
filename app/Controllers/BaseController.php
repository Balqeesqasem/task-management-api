<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\JWTAuth;

class BaseController extends ResourceController
{
    protected $user;

    // Authenticate and authorize the user
    protected function authenticateUser()
    {
        // Call the JWT library to authenticate the token
        $auth = JWTAuth::authenticate($this->request);

        // If the authentication fails, return a 401 Unauthorized response
        if ($auth['status'] !== 200) {
            // Send a response and prevent further execution
            return $this->failUnauthorized($auth['message']);
        }

        // Store the authenticated user in the class property
        $this->user = $auth['user'];

        // Return true to indicate successful authentication
        return true;
    }
}
