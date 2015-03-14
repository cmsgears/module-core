<?php
namespace cmsgears\core\frontend\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\entities\CmgFile;

use cmsgears\core\common\utilities\DateUtil;

class UserService extends cmsgears\core\common\services\UserService {

	// Static Methods ----------------------------------------------

	// Create -----------

	// User registered from website
	public static function register( $registerForm ) {

		$user 	= new User();
		$date	= DateUtil::getMysqlDate();

		$user->setEmail( $registerForm->email );
		$user->setPassword( $registerForm->password );
		$user->setUsername( $registerForm->nickName );
		$user->setFirstname( $registerForm->firstName );
		$user->setLastname( $registerForm->lastName );
		$user->setNewsletter( $registerForm->newsletter );
		$user->setRegOn( $date );
		$user->setStatus( User::STATUS_NEW );
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

		$user->setStatus( User::STATUS_ACTIVE );
		$user->unsetVerifyToken();

		$user->save();

		return true;
	}

	// User forgot password
	public static function forgotPassword( $user ) {

		$token 	= Yii::$app->getSecurity()->generateRandomString();

		$user->setResetToken( $token );

		$user->save();

		return true;
	}

	// User reset password
	public static function resetPassword( $user, $resetForm ) {

		$user->setPassword( $resetForm->password );
		$user->generateResetToken();

		if( $user->isNew() ) {

			$user->setStatus( User::STATUS_ACTIVE );
		}

		$user->save();

		return true;
	}
}

?>