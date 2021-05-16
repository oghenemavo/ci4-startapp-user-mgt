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
			$routes->post('process/edit/(:num)', 'RoleSettings::processEditRole/$1', ['as' => 'process_edit_role']); // done
        });

		$routes->group('permission-groups', function($routes) {
			$routes->get('view', 'PermissionSettings::index', ['as' => 'view_permission_group']); // done
			$routes->post('create', 'PermissionSettings::createPermissionGroup', ['as' => 'add_pg']); // done
			$routes->get('edit/(:num)', 'PermissionSettings::edit/$1', ['as' => 'edit_permission_group']); // done
			$routes->post('process/edit/(:num)', 'PermissionSettings::editPermissionGroup/$1', ['as' => 'edit_pg']); // done
		});

		$routes->group('role-permission', function($routes) {
			$routes->get('view/(:num)', 'RolePermission::index/$1', ['as' => 'view_role_permission']); // done
			$routes->post('edit/(:num)', 'RolePermission::edit/$1', ['as' => 'edit_rp']); // done
		});
    }
);