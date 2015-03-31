<?php
namespace cmsgears\core\frontend\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\entities\CmgFile;

use cmsgears\core\common\utilities\DateUtil;

class UserService extends \cmsgears\core\common\services\UserService {

	// Static Methods ----------------------------------------------

	// Create -----------

	// User registered from website
	public static function register( $registerForm ) {

		$user 	= new User();
		$date	= DateUtil::getMysqlDate();

		$user->email 		= $registerForm->email;
		$user->username 	= $registerForm->username;
		$user->firstName	= $registerForm->firstName;
		$user->lastName		= $registerForm->lastName;
		$user->newsletter	= $registerForm->newsletter;
		$user->registeredOn	= $date;
		$user->status		= User::STATUS_NEW;

		$user->setPassword( $registerForm->password );
		$user->generateVerifyToken();
		$user->generateAuthKey();

		$user->save();

		return $user;
	}

	// Update -----------

	// Activate User created from Admin Panel
	public static function activate( $user, $activateForm ) {

		$user->setPassword( $activateForm->password );
		$user->setStatus( User::STATUS_ACTIVE );
		$user->unsetResetToken();

		$user->save();

		return true;
	}

	// Verify User registered from website
	public static function verify( $user ) {

		$user->status = User::STATUS_ACTIVE;
		$user->unsetVerifyToken();

		$user->save();

		return true;
	}

	// User forgot password
	public static function forgotPassword( $user ) {

		$user->generateResetToken();

		$user->save();

		return true;
	}

	// User reset password
	public static function resetPassword( $user, $resetForm ) {

		$user->setPassword( $resetForm->password );
		$user->generateResetToken();

		if( $user->isNew() ) {

			$user->status = User::STATUS_ACTIVE;
		}

		$user->save();

		return true;
	}
}

?>