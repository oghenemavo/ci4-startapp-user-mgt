<?php

/**
 * --------------------------------------------------------------------
 * Management Module Route Definitions
 * --------------------------------------------------------------------
 */
$routes->group(
    'manage', 
    [
        'namespace' => 'Ecosystem\Management\Controllers', 
        'filter' => 'require_auth_user'
    ],
    function($routes) {
        // role settings
        $routes->group('roles', function($routes) {
            // menus for role settings
            $routes->get('/', 'RoleSettings::index');

			$routes->get('view', 'RoleSettings::viewRole', ['as' => 'access_role']); // done
			$routes->post('create', 'RoleSettings::createRole', ['as' => 'create_role']); // done
			$routes->get('edit/(:num)', 'RoleSettings::editRole/$1', ['as' => 'edit_role']); // done
			$routes->post('update/(:num)', 'RoleSettings::processEditRole/$1', ['as' => 'process_edit_role']); // done
        });

		$routes->group('permission-groups', function($routes) {
			$routes->get('view', 'PermissionSettings::index', ['as' => 'view_permission_group']); // done
			$routes->post('create', 'PermissionSettings::createPermissionGroup', ['as' => 'add_pg']); // done
			$routes->get('edit/(:num)', 'PermissionSettings::edit/$1', ['as' => 'edit_permission_group']); // done
			$routes->post('update/(:num)', 'PermissionSettings::editPermissionGroup/$1', ['as' => 'edit_pg']); // done
		});

		$routes->group('role-permission', function($routes) {
			$routes->get('view/(:num)', 'RolePermission::index/$1', ['as' => 'view_role_permission']); // done
			$routes->post('edit/(:num)', 'RolePermission::edit/$1', ['as' => 'edit_rp']); // done
		});

		// users
		$routes->get('users/list', 'User::index');
		$routes->get('show/user/(:num)', 'User::show/$1', ['as' => 'show_user']);
        $routes->get('edit/user/(:num)', 'User::edit/$1', ['as' => 'edit_user']);
        $routes->post('update/user/(:num)', 'User::update/$1', ['as' => 'update_user']);
        $routes->get('new/user', 'User::new', ['as' => 'new_user']);
        $routes->post('create/user', 'User::create', ['as' => 'create_user']);

		$routes->group('mail', function($routes) {
            // menus for mail settings
            $routes->get('clients', 'MailSettings::index');
            $routes->post('set/client', 'MailSettings::setClient', ['as' => 'set_client']);
			
		});

		$routes->group('templates', function($routes) {
            // menus for mail settings
            $routes->get('/', 'MailSettings::templates');
            $routes->get('show/(:num)', 'MailSettings::showTemplate/$1', ['as' => 'show_mail_template']);
            $routes->post('edit/(:num)', 'MailSettings::editTemplate/$1', ['as' => 'edit_mail_template']);

		});
    }
);