<?php
namespace cmsgears\core\admin\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\models\entities\Role;
use cmsgears\core\common\models\entities\RolePermission;

use cmsgears\core\common\utilities\DateUtil;

class RoleService extends \cmsgears\core\common\services\RoleService {

	// Static Methods ----------------------------------------------

	// Pagination -------

	public static function getPagination() {

	    $sort = new Sort([
	        'attributes' => [
	            'name' => [
	                'asc' => [ 'name' => SORT_ASC ],
	                'desc' => ['name' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name',
	            ]
	        ]
	    ]);

		return self::getPaginationDetails( new Role(), [ 'sort' => $sort, 'search-col' => 'name' ] );
	}

	// Create -----------

	public static function create( $role ) {
		
		// Set Attributes
		$date				= DateUtil::getMysqlDate();
		$user				= Yii::$app->user->getIdentity();
		$role->createdBy	= $user->id;
		$role->createdOn	= $date;
		
		// Create Role
		$role->save();
		
		// Return Role
		return $role;
	}

	// Update -----------

	public static function update( $role ) {

		// Find existing role
		$roleToUpdate	= self::findById( $role->id );

		// Copy and set Attributes		
		$date			= DateUtil::getMysqlDate();
		$user			= Yii::$app->user->getIdentity();

		$roleToUpdate->modifiedBy	= $user->id;
		$roleToUpdate->modifiedOn	= $date;

		$roleToUpdate->copyForUpdateFrom( $role, [ 'name', 'description', 'homeUrl' ] );
		
		// Update Role
		$roleToUpdate->update();
		
		// Return updated Role
		return $roleToUpdate;
	}

	public static function bindPermissions( $binder ) {

		$roleId			= $binder->roleId;
		$permissions	= $binder->bindedData;
		
		// Clear all existing mappings
		RolePermission::deleteByRoleId( $roleId );

		// Create updated mappings
		if( isset( $permissions ) && count( $permissions ) > 0 ) {

			foreach ( $permissions as $key => $value ) {

				if( isset( $value ) ) {

					$toSave					= new RolePermission();
					$toSave->roleId			= $roleId;
					$toSave->permissionId	= $value;

					$toSave->save();
				}
			}
		}

		return true;
	}

	// Delete -----------

	public static function delete( $role ) {

		// Find existing Role
		$roleToDelete	= self::findById( $role->id );

		// Delete Role
		$roleToDelete->delete();

		return true;
	}
}

?>