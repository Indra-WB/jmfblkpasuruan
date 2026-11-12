<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session()->get('logged_in')) {
            session()->set('redirect_after_login', (string) current_url(true));
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman tersebut.');
        }

        if ($arguments !== null && ! empty($arguments)) {
            $userRole = session()->get('role');
            if (! in_array($userRole, $arguments, true)) {
                return redirect()->to('/')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
