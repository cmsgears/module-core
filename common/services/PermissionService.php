<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Permission;
use cmsgears\core\common\models\entities\RolePermission;

/**
 * The class PermissionService is base class to perform database activities for Permission Entity.
 */
class PermissionService extends Service {

	// Static Methods ----------------------------------------------
	
	// Read ----------------

	/**
	 * @param integer $id
	 * @return Permission
	 */
	public static function findById( $id ) {

		return Permission::findById( $id );
	}

	/**
	 * @param string $id
	 * @return array - An array of associative array of permission id and name
	 */
	public static function getIdNameList() {

		return self::findIdNameList( 'id', 'name', CoreTables::TABLE_PERMISSION );
	}

	// Data Provider ----

	/**
	 * @param array $config to generate query
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $config = [] ) {

		return self::getDataProvider( new Permission(), $config );
	}

	// Create -----------

	/**
	 * @param Permission $permission
	 * @return Permission
	 */
	public static function create( $permission ) {
		
		// Set Attributes
		$user					= Yii::$app->user->getIdentity();
		$permission->createdBy	= $user->id;
		
		// Create Permission
		$permission->save();
		
		// Return Permission
		return $permission;
	}

	// Update -----------

	/**
	 * @param Permission $permission
	 * @return Permission
	 */
	public static function update( $permission ) {
		
		// Find existing Permission
		$permissionToUpdate	= self::findById( $permission->id );
		
		// Copy and set Attributes
		$user							= Yii::$app->user->getIdentity();
		$permissionToUpdate->modifiedBy	= $user->id;

		$permissionToUpdate->copyForUpdateFrom( $permission, [ 'name', 'description' ] );

		// Update Permission
		$permissionToUpdate->update();

		// Return updated Permission
		return $permission;
	}

	/**
	 * @param BinderForm $binder
	 * @return boolean
	 */
	public static function bindRoles( $binder ) {

		$permissionId	= $binder->binderId;
		$roles			= $binder->bindedData;
		
		// Clear all existing mappings
		RolePermission::deleteByPermissionId( $permissionId );
		
		// Create updated mappings
		if( isset( $roles ) && count( $roles ) > 0 ) {

			foreach ( $roles as $key => $value ) {

				if( isset( $value ) && $value > 0 ) {

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

	/**
	 * @param Permission $permission
	 * @return boolean
	 */
	public static function delete( $permission ) {

		// Find existing Permisison
		$permisisonToDelete	= self::findById( $permission->id );

		// Delete Permission
		$permisisonToDelete->delete();

		return true;
	}
}

?>