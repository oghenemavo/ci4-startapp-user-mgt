<?php

namespace Ecosystem\Authentication\Filters;

use CodeIgniter\HTTP\{RequestInterface, ResponseInterface};
use CodeIgniter\Filters\FilterInterface;
use Config\Services;

class RedirectOnAuth implements FilterInterface {

    public function before(RequestInterface $request, $arguments = null) {
        // Do something here
        return service('userLib')->require_guest();
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {
        // Do something here
    }

}