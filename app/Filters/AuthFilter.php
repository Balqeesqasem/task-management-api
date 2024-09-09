<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Libraries\JWTAuth;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $auth = JWTAuth::authenticate($request);
        if ($auth['status'] !== 200) {
            return \Config\Services::response()
                ->setStatusCode($auth['status'])
                ->setJSON(['message' => $auth['message']]);
        }

        // Set the authenticated user in the request so that it can be accessed in controllers
        $request->user = $auth['user'];
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No post-processing needed
    }
}
