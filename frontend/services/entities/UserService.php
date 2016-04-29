<?php
namespace cmsgears\core\frontend\services\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\resources\CmgFile;

use cmsgears\core\common\services\resources\FileService;

use cmsgears\core\common\utilities\DateUtil;

class UserService extends \cmsgears\core\common\services\entities\UserService {

	// Static Methods ----------------------------------------------

	// Create -----------

	/**
	 * The method registers website users and set their status to new at start. It also generate verification token.
	 * @param RegisterForm $registerForm
	 * @return User
	 */
	public static function register( $registerForm ) {

		$user 	= new User();
		$date	= DateUtil::getDateTime();

		$user->email 		= $registerForm->email;
		$user->username 	= $registerForm->username;
		$user->firstName	= $registerForm->firstName;
		$user->lastName		= $registerForm->lastName;
		$user->registeredAt	= $date;
		$user->status		= User::STATUS_NEW;

		$user->generatePassword( $registerForm->password );
		$user->generateVerifyToken();
		$user->generateAuthKey();

		$user->save();

		return $user;
	}

	// Update -----------

	/**
	 * The method verify and confirm user by accepting valid token sent via mail. It also set user status to active.
	 * @param User $user
	 * @param string $token
	 * @param boolean $activate
	 * @return boolean
	 */
	public static function verify( $user, $token, $activate = true ) {

		// Check Token
		if( $user->isVerifyTokenValid( $token ) ) {

			// Find existing user
			$userToUpdate	= User::findById( $user->id );

			// Activate User
			if( $activate ) {

				$userToUpdate->status = User::STATUS_ACTIVE;
			}
			else {

				$userToUpdate->status = User::STATUS_CONFIRMED;
			}

			$userToUpdate->unsetVerifyToken();

			$userToUpdate->update();

			return true;
		}

		return false;
	}
}

?>