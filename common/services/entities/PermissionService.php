<?php
namespace cmsgears\core\common\services\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\Permission;
use cmsgears\core\common\models\mappers\RolePermission;

/**
 * The class PermissionService is base class to perform database activities for Permission Entity.
 */
class PermissionService extends \cmsgears\core\common\services\base\Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	/**
	 * @param integer $id
	 * @return Permission
	 */
	public static function findById( $id ) {

		return Permission::findById( $id );
	}

	public static function findByParentId( $id ) {

		return Permission::findByParentId( $id );
	}

	/**
	 * @param string $slug
	 * @return Permission
	 */
	public static function findBySlug( $slug ) {

		return Permission::findBySlug( $slug );
	}

	// Read - Maps -----

	/**
	 * @param array $config
	 * @return array - an array having id as key and name as value.
	 */
	public static function getIdNameMap( $config = [] ) {

		return self::findMap( 'id', 'name', CoreTables::TABLE_PERMISSION, $config );
	}

	/**
	 * @param array $config
	 * @return array - an array having id as key and name as value.
	 */
	public static function getIdNameMapByType( $type, $config = [] ) {

		$config[ 'conditions' ][ 'type' ] 	= $type;

		return self::getIdNameMap( $config );
	}

	// Read - Lists ----

	/**
	 * @param array $config
	 * @return array - An array of associative array of permission id and name.
	 */
	public static function getIdNameList( $config = [] ) {

		return self::findIdNameList( 'id', 'name', CoreTables::TABLE_PERMISSION, $config );
	}

	/**
	 * @param string $id
	 * @return array - An array of associative array of permission id and name.
	 */
	public static function getIdNameListByType( $type ) {

		if( isset( $type ) ) {

			return self::getIdNameList( [ 'conditions' => [ 'type' => $type ] ] );
		}

		return self::getIdNameList();
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