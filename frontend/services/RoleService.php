<?php
namespace cmsgears\core\frontend\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\Role;

class RoleService extends \cmsgears\core\common\services\UserService {

	// Static Methods ----------------------------------------------

	// Update --------------
	
	/**
	 * @param User $user
	 * @param string $roleName
	 * @return User
	 */
	public static function assignRole( $user, $roleName ) {

		$userToUpdate			= UserService::findById( $user->id );

		// Assign Role
		$role 					= Role::findByName( $roleName );
		$userToUpdate->roleId	= $role->id;

		// Update User
		$userToUpdate->update();
	}
}

?>