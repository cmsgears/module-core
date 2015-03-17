<?php
namespace cmsgears\core\admin\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\models\entities\User;

use cmsgears\core\common\utilities\DateUtil;

class UserService extends \cmsgears\core\common\services\UserService {

	// Static Methods ----------------------------------------------

	// Pagination -------

	public static function getPagination( $conditions = [] ) {

	    $sort = new Sort([
	        'attributes' => [
	            'name' => [
	                'asc' => [ 'firstName' => SORT_ASC, 'lastName' => SORT_ASC ],
	                'desc' => [ 'firstName' => SORT_DESC, 'lastName' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name',
	            ],
	            'username' => [
	                'asc' => [ 'username' => SORT_ASC ],
	                'desc' => ['username' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'username',
	            ],
	            'role' => [
	                'asc' => [ 'roleId' => SORT_ASC ],
	                'desc' => ['roleId' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'role',
	            ],
	            'status' => [
	                'asc' => [ 'status' => SORT_ASC ],
	                'desc' => ['status' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'status',
	            ],
	            'email' => [
	                'asc' => [ 'email' => SORT_ASC ],
	                'desc' => ['email' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'email',
	            ]
	        ]
	    ]);
		
		$status	= Yii::$app->request->getQueryParam( "status" );

		if( isset( $status ) ) {

			$conditions['status'] = $status;
		}

		return self::getPaginationDetails( new User(), [ 'conditions' => $conditions, 'sort' => $sort, 'search-col' => 'firstName' ] );
	}

	public static function getPaginationByNewsletter() {

		return self::getPagination( [ 'newsletter' => 1 ] );
	}

	// Create -----------

	// User created from Admin Panel
	public static function create( $user ) {

		// Set Attributes
		$date				= DateUtil::getMysqlDate();
		$user->registeredOn = $date;
		$user->status		= User::STATUS_NEW;
		
		// Generate Tokens
		$user->generateVerifyToken();
		$user->generateAuthKey();
		
		// Create User
		$user->save();

		// Return User
		return $user;
	}

	// Update -----------

	public static function update( $user, $avatar ) {
		
		// Find existing user
		$userToUpdate	= User::findById( $user->id );

		// Copy Attributes		
		$userToUpdate->copyForUpdateFrom( $user, [ 'email', 'username', 'firstName', 'lastName', 'newsletter', 'status', 'roleId', 'phone', 'avatarId' ] );

		// Save Avatar
		FileService::saveImage( $avatar, $userToUpdate, $userToUpdate, 'avatarId', Yii::$app->fileManager );

		// Update User
		$userToUpdate->update();
		
		// Return updated User
		return $userToUpdate;
	}

	// Delete -----------

	public static function delete( $user ) {

		// Find existing user
		$userToDelete	= User::findById( $user->getId() );

		// Delete User
		$userToDelete->delete();

		return true;
	}
}

?>