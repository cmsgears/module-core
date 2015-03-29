<?php
namespace cmsgears\core\frontend\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\Role;

class RoleService extends \cmsgears\core\common\services\UserService {

	// Static Methods ----------------------------------------------

	// Update --------------

	public static function assignRole( $user, $name ) {

		// Assign Role	
		$role 			= Role::findByName( $name );
		$user->roleId	= $role->id;

		// Update User
		$user->update();
	}
}

?>