<?php

namespace Ecosystem\Authentication\Filters;

use CodeIgniter\HTTP\{RequestInterface, ResponseInterface};
use CodeIgniter\Filters\FilterInterface;

class RequireUserAuthorization implements FilterInterface {

    public function before(RequestInterface $request, $arguments = null) {
        // Do something here
        return service('userLib')->require_login();
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {
        // Do something here
    }

}