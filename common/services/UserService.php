<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\CoreTables;
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

	/**
	 * @param string $roleSlug
	 * @return array
	 */
	public static function getIdNameMapByRoleSlug( $roleSlug ) {

		$roleTable			= CoreTables::TABLE_ROLE;
		$userTable			= CoreTables::TABLE_USER;
		$siteTable			= CoreTables::TABLE_SITE;
		$siteMemberTable	= CoreTables::TABLE_SITE_MEMBER;

		$users 				= User::find()
								->leftJoin( $siteMemberTable, "$siteMemberTable.userId = $userTable.id" )
								->leftJoin( $siteTable, "$siteTable.id = $siteMemberTable.siteId" )
								->leftJoin( $roleTable, "$roleTable.id = $siteMemberTable.roleId" )
								->where( "$roleTable.slug=:slug AND $siteTable.name=:name", [ ':slug' => $roleSlug, ':name' => Yii::$app->cmgCore->getSiteName() ] )->all();
		$usersMap			= [];

		foreach ( $users as $user ) {

			$usersMap[ $user->id ] = $user->getName();
		}

		return $usersMap;
	}

	public static function findAttributeMapByType( $user, $type ) {

		return ModelAttributeService::findByType( $user->id, CoreGlobal::TYPE_USER, $type );
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

		if( $user->genderId <= 0 ) {

			unset( $user->genderId );
		}

		// Set Attributes
		$user->registeredAt = DateUtil::getDateTime();
		$user->status		= User::STATUS_NEW;

		// Generate Tokens
		$user->generateVerifyToken();
		$user->generateAuthKey();

		// Save Files
		FileService::saveFiles( $user, [ 'avatarId' => $avatar ] );

		// Create User
		$user->save();

		// Add to mailing list
		if( $user->newsletter ) {

			NewsletterMemberService::create( $user->email, $user->getName() );
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

		if( $user->genderId <= 0 ) {

			unset( $user->genderId );
		}

		// Find existing user
		$userToUpdate	= User::findById( $user->id );

		// Copy Attributes
		$userToUpdate->copyForUpdateFrom( $user, [ 'avatarId', 'genderId', 'email', 'username', 'firstName', 'lastName', 'status', 'phone', 'avatarUrl', 'websiteUrl' ] );

		// Save Files
		FileService::saveFiles( $userToUpdate, [ 'avatarId' => $avatar ] );

		// Update User
		$userToUpdate->update();

		// Update mailing list
		NewsletterMemberService::update( $user->email, $user->getName(), $user->newsletter );

		// Return updated User
		return $userToUpdate;
	}

	public static function checkNewsletterMember( $user ) {

		$member = NewsletterMemberService::findByEmail( $user->email );

		// Update mailing list
		if( isset( $member ) && $member->active ) {

			$user->newsletter = true;
		}
	}

	public static function updateAttributes( $user, $attributes ) {

		foreach ( $attributes as $attribute ) {

			ModelAttributeService::update( $attribute );
		}

		return true;
	}

    /**
     * Activate User created from Admin Panel.
     * @param User $user
     * @param ResetPasswordForm $resetForm
     * @param boolean $activate
     * @return boolean
     */
    public static function activate( $user, $resetForm, $activate = true ) {

        // Find existing user
        $userToUpdate   = User::findById( $user->id );

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
     * The method generate a new reset token which can be used later to update user password.
     * @param User $user
     * @return User
     */
    public static function forgotPassword( $user ) {

        // Find existing user
        $userToUpdate   = User::findById( $user->id );

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
        $userToUpdate   = User::findById( $user->id );

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
        $userToUpdate   = User::findById( $user->id );

        // Save Avatar
        FileService::saveImage( $avatar, [ 'model' => $userToUpdate, 'attribute' => 'avatarId' ] );

        // Update User
        $userToUpdate->update();

        // Return updated User
        return $userToUpdate;
    }

	// Delete -----------

	/**
	 * The method delete existing user.
	 * @param User $user
	 * @return boolean
	 */
	public static function delete( $user, $avatar = null ) {

		// Find existing user
		$userToDelete	= User::findById( $user->id );

		// Delete User
		$userToDelete->delete();

		// Delete Files
		FileService::deleteFiles( [ $avatar ] );

		return true;
	}
}

?>