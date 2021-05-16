<?php

namespace Ecosystem\Management\Config;

use CodeIgniter\Config\BaseService;
use Ecosystem\Management\Libraries\PermissionsLib;

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
}
