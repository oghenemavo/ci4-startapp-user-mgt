<?php

/**
 * --------------------------------------------------------------------
 * Signup Route Definitions
 * --------------------------------------------------------------------
 */
$routes->group(
    'signup', 
    [
        'namespace' => 'Ecosystem\Authentication\Controllers', 
        'filter' => 'redirect_auth_user'
    ], 
    function($routes) {
        $routes->get('/', 'Signup::index');
        $routes->post('process', 'Signup::process');

    }
);

/**
 * --------------------------------------------------------------------
 * Verify Route Definitions
 * --------------------------------------------------------------------
 */
$routes->group(
    'account', 
    [
        'namespace' => 'Ecosystem\Authentication\Controllers', 
        'filter' => 'redirect_auth_user'
    ], 
    function($routes) {
        // Custom Placeholders
        $routes->addPlaceholder('token', '[\da-f]+');

        $routes->get('verify/(:token)', 'VerifyAccount::index/$1', ['as' => 'verify']);
        $routes->post('verification/(:token)', 'VerifyAccount::process/$1', ['as' => 'account-verify']);
        $routes->get('resend/verification/(:token)', 'VerifyAccount::resendVerification/$1', ['as' => 'resend_verification']);
    }
);

/**
 * --------------------------------------------------------------------
 * Login Route Definitions
 * --------------------------------------------------------------------
 */
$routes->group(
    'login', 
    [
        'namespace' => 'Ecosystem\Authentication\Controllers', 
        'filter' => 'redirect_auth_user'
    ], 
    function($routes) {
        // Custom Placeholders
        $routes->addPlaceholder('htkn', '[\da-f]+');

        $routes->get('/', 'Login::index');
        $routes->match(['get', 'post'], 'process', 'Login::process');
        $routes->get('success', 'Login::success');
        
    }
);

$routes->add('logout', '\Ecosystem\Authentication\Controllers\Login::logout');

$routes->add('dashboard', function()
{
    echo 'welcome';
}, ['filter' => 'require_auth_user']);