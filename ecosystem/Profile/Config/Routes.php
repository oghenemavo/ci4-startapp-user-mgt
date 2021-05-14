<?php

/**
 * --------------------------------------------------------------------
 * Profile & Settings Route Definitions
 * --------------------------------------------------------------------
 */
$routes->group(
    'user', 
    [
        'namespace' => 'Ecosystem\Profile\Controllers', 
        'filter' => 'require_auth_user'
    ], 
    function($routes) {
        $routes->group('profile', function($routes) {
            $routes->get('/', 'User::index');
            $routes->get('edit', 'User::profile');
            $routes->post('edit/process', 'User::editProfile', ['as' => 'user_edit_profile']);
        });

		$routes->group('settings', function($routes) {
            $routes->get('/', 'Settings::index');
            $routes->post('email', 'Settings::editEmail', ['as' => 'change_email']);
            $routes->post('password', 'Settings::editPassword', ['as' => 'change_password']);
        });
        
    }
);