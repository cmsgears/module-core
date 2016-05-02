<?php
namespace cmsgears\core\common\services\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\Role;
use cmsgears\core\common\models\mappers\RolePermission;

/**
 * The class RoleService is base class to perform database activities for Role Entity.
 */
class RoleService extends \cmsgears\core\common\services\base\Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	/**
	 * @param integer $id
	 * @return Role
	 */
	public static function findById( $id ) {

		return Role::findById( $id );
	}

	public static function findByParentId( $id ) {

		return Role::findByParentId( $id );
	}

	/**
	 * @param string $name
	 * @return Role
	 */
	public static function findByName( $name ) {

		return Role::findByName( $name );
	}

	/**
	 * @param string $slug
	 * @return Role
	 */
	public static function findBySlug( $slug ) {

		return Role::findBySlug( $slug );
	}

	// Read - Lists ----

	/**
	 * @param array $config
	 * @return array - An array of associative array of role id and name.
	 */
	public static function getIdNameList( $config = [] ) {

		return self::findIdNameList( 'id', 'name', CoreTables::TABLE_ROLE, $config );
	}

	/**
	 * @param string $id
	 * @return array - An array of associative array of role id and name.
	 */
	public static function getIdNameListByType( $type ) {

		if( isset( $type ) ) {

			return self::getIdNameList( [ 'conditions' => [ 'type' => $type ] ] );
		}

		return self::getIdNameList();
	}

	// Read - Maps -----

	/**
	 * @param array $config
	 * @return array - an array having id as key and name as value.
	 */
	public static function getIdNameMap( $config = [] ) {

		return self::findMap( 'id', 'name', CoreTables::TABLE_ROLE, $config );
	}

	/**
	 * @param array $config
	 * @return array - an array having id as key and name as value.
	 */
	public static function getIdNameMapByType( $type, $config = [] ) {

		$config[ 'conditions' ][ 'type' ] 	= $type;

		return self::getIdNameMap( $config );
	}

	/**
	 * @return array - an array having id as key and name as value.
	 */
	public static function getIdNameMapByRoles( $roles ) {

		return self::findMap( 'id', 'name', CoreTables::TABLE_ROLE, [ 'filters' => [ [ 'in', 'slug', $roles ] ], 'prepend' => [ [ 'name' => '0', 'value' => 'Choose Role' ] ] ] );
	}

	// Data Provider ----

	/**
	 * @param array $config to generate query
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $config = [] ) {

		return self::getDataProvider( new Role(), $config );
	}

	// Create -----------

	/**
	 * @param Role $role
	 * @return Role
	 */
	public static function create( $role ) {

		// Create Role
		$role->save();

		// Return Role
		return $role;
	}

	// Update -----------

	/**
	 * @param Role $role
	 * @return Role
	 */
	public static function update( $role ) {

		// Find existing role
		$roleToUpdate	= self::findById( $role->id );

		// Copy and set Attributes
		$roleToUpdate->copyForUpdateFrom( $role, [ 'name', 'description', 'homeUrl' ] );

		// Update Role
		$roleToUpdate->update();

		// Return updated Role
		return $roleToUpdate;
	}

	/**
	 * @param BinderForm $binder
	 * @return boolean
	 */
	public static function bindPermissions( $binder ) {

		$roleId			= $binder->binderId;
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

	/**
	 * @param Role $role
	 * @return boolean
	 */
	public static function delete( $role ) {

		// Find existing Role
		$roleToDelete	= self::findById( $role->id );

		// Delete Role
		$roleToDelete->delete();

		return true;
	}
}

?>