<?php

namespace Ecosystem\Management\Config;

use CodeIgniter\Config\BaseService;
use Ecosystem\Management\Libraries\{PermissionsLib, ManageLib};

class Services extends BaseService
{
    /**
     * permissionsLib Service
     *
     * @param boolean $getShared
     * @return void
     */
	public static function permissionsLib($getShared = true) {
        if ($getShared) {
            return static::getSharedInstance('permissionsLib');
        }
        return new PermissionsLib();
    }

	public static function manageLib($getShared = true) {
        if ($getShared) {
            return static::getSharedInstance('manageLib');
        }
        return new ManageLib();
    }
}
