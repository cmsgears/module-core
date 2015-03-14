<?php
namespace cmsgears\core\admin\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\models\entities\Role;
use cmsgears\core\common\models\entities\RolePermission;

class RoleService extends \cmsgears\core\common\services\RoleService {

	// Static Methods ----------------------------------------------

	// Pagination -------

	public static function getPagination() {

	    $sort = new Sort([
	        'attributes' => [
	            'name' => [
	                'asc' => [ 'role_name' => SORT_ASC ],
	                'desc' => ['role_name' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name',
	            ]
	        ]
	    ]);

		return self::getPaginationDetails( new Role(), [ 'sort' => $sort, 'search-col' => 'role_name' ] );
	}

	// Create -----------

	public static function create( $role ) {

		$role->save();

		return true;
	}

	// Update -----------

	public static function update( $role ) {

		$roleToUpdate	= self::findById( $role->getId() );

		$roleToUpdate->setName( $role->getName() );
		$roleToUpdate->setDesc( $role->getDesc() );
		$roleToUpdate->setHome( $role->getHome() );

		$roleToUpdate->update();

		return true;
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

					$toSave	= new RolePermission();
	
					$toSave->setRoleId( $roleId );
					$toSave->setPermissionId( $value );

					$toSave->save();
				}
			}
		}

		return true;
	}

	public static function assignRole( $name, $user ) {
		
		$role = Role::findByName( $name );

		$user->setRoleId( $role->getId() );

		$user->update();
	}

	// Delete -----------

	public static function delete( $role ) {

		$role->delete();

		return true;
	}
}

?>