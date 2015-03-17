<?php
namespace cmsgears\core\admin\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\models\entities\Permission;
use cmsgears\core\common\models\entities\RolePermission;

use cmsgears\core\common\utilities\DateUtil;

class PermissionService extends \cmsgears\core\common\services\PermissionService {

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

		return self::getPaginationDetails( new Permission(), [ 'sort' => $sort, 'search-col' => 'name' ] );
	}

	// Create -----------
	
	public static function create( $permission ) {
		
		// Set Attributes
		$date					= DateUtil::getMysqlDate();
		$user					= Yii::$app->user->getIdentity();
		$permission->createdBy	= $user->id;
		$permission->createdOn	= $date;
		
		// Create Permission
		$permission->save();
		
		// Return Permission
		return $permission;
	}

	// Update -----------

	public static function update( $permission ) {
		
		// Find existing Permission
		$permissionToUpdate	= self::findById( $permission->id );
		
		// Copy and set Attributes
		$date							= DateUtil::getMysqlDate();
		$user							= Yii::$app->user->getIdentity();
		$permissionToUpdate->modifiedBy	= $user->id;
		$permissionToUpdate->modifiedOn	= $date;

		$permissionToUpdate->copyForUpdateFrom( $permission, [ 'name', 'description' ] );

		// Update Permission
		$permissionToUpdate->update();

		// Return updated Permission
		return $permission;
	}

	public static function bindRoles( $binder ) {

		$permissionId	= $binder->permissionId;
		$roles			= $binder->bindedData;
		
		// Clear all existing mappings
		RolePermission::deleteByPermissionId( $permissionId );
		
		// Create updated mappings
		if( isset( $roles ) && count( $roles ) > 0 ) {

			foreach ( $roles as $key => $value ) {
				
				if( isset( $value ) ) {

					$toSave					= new RolePermission();
					$toSave->permissionId	= $permissionId;
					$toSave->roleId			= $value;

					$toSave->save();
				}
			}
		}

		return true;
	}

	// Delete -----------

	public static function delete( $permission ) {

		// Find existing Permisison
		$permisisonToDelete	= self::findById( $permission->id );

		// Delete Permission
		$permisisonToDelete->delete();

		return true;
	}
}

?>