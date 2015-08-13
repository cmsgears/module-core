<?php
namespace cmsgears\core\frontend\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\entities\CmgFile;

use cmsgears\core\common\services\FileService;
use cmsgears\core\common\services\NewsletterMemberService;

use cmsgears\core\common\utilities\DateUtil;

class UserService extends \cmsgears\core\common\services\UserService {

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
		$user->newsletter	= $registerForm->newsletter;
		$user->registeredAt	= $date;
		$user->status		= User::STATUS_NEW;

		$user->generatePassword( $registerForm->password );
		$user->generateVerifyToken();
		$user->generateAuthKey();

		$user->save();

		// Add to mailing list
		if( $user->newsletter ) {

			NewsletterMemberService::create( $user->email );
		}

		return $user;
	}

	// Update -----------

	/**
	 * Activate User created from Admin Panel.
	 * @param User $user
	 * @param ResetPasswordForm $resetForm
	 * @param boolean $activate
	 * @return boolean
	 */
	public static function activate( $user, $resetForm, $activate = true ) {

		// Find existing user
		$userToUpdate	= User::findById( $user->id );

		// Generate Password
		$userToUpdate->generatePassword( $resetForm->password );

		// Activate User
		if( $activate ) {

			$userToUpdate->status = User::STATUS_ACTIVE;
		}

		$userToUpdate->unsetResetToken();

		$userToUpdate->update();

		return true;
	}

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

			$userToUpdate->unsetVerifyToken();

			$userToUpdate->update();

			return true;
		}

		return false;
	}

	/**
	 * The method generate a new reset token which can be used later to update user password.
	 * @param User $user
	 * @return User
	 */
	public static function forgotPassword( $user ) {

		// Find existing user
		$userToUpdate	= User::findById( $user->id );

		// Generate Token
		$userToUpdate->generateResetToken();

		// Update User
		$userToUpdate->update();

		return $userToUpdate;
	}

	/**
	 * The method generate a new secure password for the given password and unset the reset token. It also activate user.
	 * @param User $user
	 * @param ResetPasswordForm $resetForm
	 * @param boolean $activate
	 * @return User
	 */
	public static function resetPassword( $user, $resetForm, $activate = true ) {

		// Find existing user
		$userToUpdate	= User::findById( $user->id );

		// Generate Password
		$userToUpdate->generatePassword( $resetForm->password );
		$userToUpdate->unsetResetToken();

		// Activate User
		if( $userToUpdate->isNew() && $activate ) {

			$userToUpdate->status = User::STATUS_ACTIVE;
		}

		// Update User
		$userToUpdate->update();

		return $userToUpdate;
	}
	
	/**
	 * The method create user avatar if it does not exist or save existing avatar.
	 * @param User $user
	 * @param CmgFile $avatar
	 * @return User - updated User
	 */
	public function updateAvatar( $user, $avatar ) {

		// Find existing user
		$userToUpdate	= User::findById( $user->id );

		// Save Avatar
		FileService::saveImage( $avatar, [ 'model' => $userToUpdate, 'attribute' => 'avatarId' ] );

		// Update User
		$userToUpdate->update();

		// Return updated User
		return $userToUpdate;
	}
}

?>