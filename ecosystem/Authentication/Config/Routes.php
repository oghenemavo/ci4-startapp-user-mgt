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
        $routes->post('verification/(:token)', 'VerifyAccount::process/$1', ['as' => 'account_verify']);
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
        $routes->get('/', 'Login::index');
        $routes->match(['get', 'post'], 'process', 'Login::process');
        $routes->get('success', 'Login::success');
    }
);

$routes->add('logout', '\Ecosystem\Authentication\Controllers\Login::logout');

/**
 * --------------------------------------------------------------------
 * Recover Account Route Definitions
 * --------------------------------------------------------------------
 */
$routes->group(
    '', 
    [
        'namespace' => 'Ecosystem\Authentication\Controllers', 
        'filter' => 'redirect_auth_user'
    ], 
    function($routes) {
        // Custom Placeholders
        $routes->addPlaceholder('token', '[\da-f]+');

        $routes->group('recover', function($routes) {
            $routes->get('forgot-password', 'RecoverAccount::index');
            $routes->post('forgot-password/process', 'RecoverAccount::forgotPasswordProcess');
        });

        // Reset Pssword
        $routes->group('reset', function($routes) {
            $routes->get('password/(:token)', 'RecoverAccount::process/$1');
            $routes->post('password/process/(:token)', 'RecoverAccount::resetPassword/$1', ['as' => 'reset']);
        });
    }
);

$routes->add('dashboard', function()
{
    echo 'welcome';
}, ['filter' => 'require_auth_user']);