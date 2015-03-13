<?php
namespace cmsgears\modules\core\admin\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\modules\core\common\models\entities\User;

use cmsgears\modules\core\common\utilities\DateUtil;

class UserService extends \cmsgears\modules\core\common\services\UserService {

	// Static Methods ----------------------------------------------

	// Pagination -------

	public static function getPagination( $newsletter = null ) {

	    $sort = new Sort([
	        'attributes' => [
	            'name' => [
	                'asc' => [ 'user_firstname' => SORT_ASC, 'user_lastname' => SORT_ASC ],
	                'desc' => [ 'user_firstname' => SORT_DESC, 'user_lastname' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name',
	            ],
	            'username' => [
	                'asc' => [ 'user_username' => SORT_ASC ],
	                'desc' => ['user_username' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'username',
	            ],
	            'role' => [
	                'asc' => [ 'user_role' => SORT_ASC ],
	                'desc' => ['user_role' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'role',
	            ],
	            'status' => [
	                'asc' => [ 'user_status' => SORT_ASC ],
	                'desc' => ['user_status' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'status',
	            ],
	            'email' => [
	                'asc' => [ 'user_email' => SORT_ASC ],
	                'desc' => ['user_email' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'email',
	            ]
	        ]
	    ]);
		
		$status		= Yii::$app->request->getQueryParam( "status" );
		$conditions	= [];

		if( isset( $status ) ) {

			$conditions['user_status'] = $status;
		}

		if( isset( $newsletter ) ) {

			$conditions['user_newsletter'] = 1;
		}

		if( count( $conditions ) > 0 ) {

			return self::getPaginationDetails( new User(), [ 'conditions' => $conditions, 'sort' => $sort, 'search-col' => 'user_firstname' ] );
		}
		else {

			return self::getPaginationDetails( new User(), [ 'sort' => $sort, 'search-col' => 'user_firstname' ] );
		}
	}

	public static function getPaginationByNewsletter() {

		return self::getPagination( 1 );
	}

	// Create -----------

	// User created from Admin Panel
	public static function create( $user ) {

		$date	= DateUtil::getMysqlDate();

		$user->setRegOn( $date );
		$user->setStatus( User::STATUS_NEW );
		$user->generateVerifyToken();
		$user->generateAuthKey();

		$user->save();

		return true;
	}

	// Update -----------

	public static function update( $user, $avatar ) {

		$existingUser	= User::findById( $user->user_id );

		$existingUser->setEmail( $user->getEmail() );
		$existingUser->setUsername( $user->getUsername() );
		$existingUser->setFirstname( $user->getFirstname() );
		$existingUser->setLastname( $user->getLastName() );
		$existingUser->setNewsletter( $user->getNewsletter() );
		$existingUser->setStatus( $user->getStatus() );
		$existingUser->setRoleId( $user->getRoleId() );
		$existingUser->setMobile( $user->getMobile() );
		$existingUser->setAvatarId( $user->getAvatarId() );

		// Save Avatar
		FileService::saveImage( $avatar, $user, Yii::$app->fileManager );

		// New Avatar
		$avatarId 	= $avatar->getId();

		if( isset( $avatarId ) && intval( $avatarId ) > 0 ) {

			$existingUser->setAvatarId( $avatarId );
		}

		$existingUser->update();

		return true;
	}

	// Delete -----------

	public static function delete( $user ) {

		$existingUser	= User::findById( $user->getId() );

		$existingUser->delete();

		return true;
	}
}

?>