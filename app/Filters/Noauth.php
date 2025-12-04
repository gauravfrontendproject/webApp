<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Noauth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // use the exact key you set in session (here: isLoggedIn)
        if (session()->get('isLoggedIn')) {
            // return the redirect response to stop further execution
            return redirect()->to('/dashboard');
        }

        // return null to continue request
        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nothing needed here
    }
}
