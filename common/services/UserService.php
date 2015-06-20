<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\User;

use cmsgears\core\common\utilities\DateUtil;

/**
 * The class UserService is base class to perform database activities for User Entity.
 */
class UserService extends Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	/**
	 * @param integer $id
	 * @return User
	 */
	public static function findById( $id ) {

		return User::findById( $id );
	}

	/**
	 * @param string $token
	 * @return User
	 */
	public static function findByAccessToken( $token ) {

		return User::findByAccessToken( $token );
	}

	/**
	 * @param string $email
	 * @return User
	 */
	public static function findByEmail( $email ) {

		return User::findByEmail( $email );
	}

	/**
	 * @param string $email
	 * @return boolean
	 */
	public static function isExistByEmail( $email ) {

		$user = User::findByEmail( $email );

		return isset( $user );
	}

	/**
	 * @param string $email
	 * @return User
	 */
	public static function findByUsername( $username ) {

		return User::findByUsername( $username );
	}

	/**
	 * @param string $username
	 * @return boolean
	 */
	public static function isExistByUsername( $username ) {

		$user = User::findByUsername( $username );

		return isset( $user );
	}

	// Data Provider ----

	/**
	 * @param array $config to generate query
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $config = [] ) {

		return self::getDataProvider( new User(), $config );
	}

	// Create -----------

	/**
	 * The method create user.
	 * @param User $user
	 * @param CmgFile $avatar
	 * @return User
	 */
	public static function create( $user, $avatar = null ) {

		// Set Attributes
		$user->registeredAt = DateUtil::getDateTime();
		$user->status		= User::STATUS_NEW;

		// Generate Tokens
		$user->generateVerifyToken();
		$user->generateAuthKey();

		if( isset( $avatar ) ) {

			// Save Avatar
			FileService::saveImage( $avatar, $user, [ 'model' => $user, 'attribute' => 'avatarId' ] );
		}

		// Create User
		$user->save();

		// Add to mailing list
		if( $user->newsletter ) {

			NewsletterMemberService::create( $user->email );
		}

		return $user;
	}

	// Update -----------

	/**
	 * The method update user including avatar.
	 * @param User $user
	 * @param CmgFile $avatar
	 * @return User
	 */
	public static function update( $user, $avatar = null ) {

		// Find existing user
		$userToUpdate	= User::findById( $user->id );

		// Copy Attributes
		$userToUpdate->copyForUpdateFrom( $user, [ 'avatarId', 'genderId', 'email', 'username', 'firstName', 'lastName', 'status', 'phone' ] );

		if( isset( $avatar ) ) {

			// Save Avatar
			FileService::saveImage( $avatar, [ 'model' => $userToUpdate, 'attribute' => 'avatarId' ] );
		}

		// Update User
		$userToUpdate->update();

		// Update mailing list
		NewsletterMemberService::update( $user->email, $user->newsletter );

		// Return updated User
		return $userToUpdate;
	}

	public static function checkNewsletterMember( $user ) {

		$member = NewsletterMemberService::findByEmail( $user->email );

		// Update mailing list
		if( isset( $member ) ) {

			$user->newsletter = true;
		}
	}

	// Delete -----------

	/**
	 * The method delete existing user.
	 * @param User $user
	 * @return boolean
	 */
	public static function delete( $user ) {

		// Find existing user
		$userToDelete	= User::findById( $user->id );

		// Delete User
		$userToDelete->delete();

		return true;
	}
}

?>