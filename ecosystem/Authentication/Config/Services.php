<?php

namespace Ecosystem\Authentication\Config;

use CodeIgniter\Config\BaseService;
use Ecosystem\Authentication\Libraries\{SignupLib, AccountVerificationLib, MailerLib, CIMailerLib};

class Services extends BaseService
{
	public static function accountLib($getShared = true) {
        if ($getShared) {
            return static::getSharedInstance('accountLib');
        }
        return new AccountVerificationLib();
    }

	public static function signupLib($getShared = true) {
        if ($getShared) {
            return static::getSharedInstance('signupLib');
        }
        return new SignupLib();
    }

    /**
     * Mail Dispatch Library
     *
     * @param boolean $getShared
     * @return void
     */
    public static function mailerLib($getShared = true) {
        if ($getShared) {
            return static::getSharedInstance('mailerLib');
        }
        return new MailerLib();
    }

    /**
     * CIMailer Library
     *
     * @param boolean $getShared
     * @return object Ecosystem\Authentication\Libraries\MailerLib 
     */
    public static function ciMailerLib($getShared = true) {
        if ($getShared) {
            return static::getSharedInstance('ciMailerLib');
        }
        return new CIMailerLib();
    }
    
}
