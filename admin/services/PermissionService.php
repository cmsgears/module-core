<?php
namespace cmsgears\core\admin\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\models\entities\Permission;
use cmsgears\core\common\models\entities\RolePermission;

class PermissionService extends \cmsgears\core\common\services\PermissionService {

	// Static Methods ----------------------------------------------

	// Pagination -------

	public static function getPagination() {

	    $sort = new Sort([
	        'attributes' => [
	            'name' => [
	                'asc' => [ 'permission_name' => SORT_ASC ],
	                'desc' => ['permission_name' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name',
	            ]
	        ]
	    ]);

		return self::getPaginationDetails( new Permission(), [ 'sort' => $sort, 'search-col' => 'permission_name' ] );
	}

	// Create -----------
	
	public static function create( $permission ) {

		$permission->save();

		return true;
	}

	// Update -----------

	public static function update( $permission ) {

		$permissionToUpdate	= self::findById( $permission->getId() );

		$permissionToUpdate->setName( $permission->getName() );
		$permissionToUpdate->setDesc( $permission->getDesc() );

		$permissionToUpdate->update();

		return true;
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

					$toSave		= new RolePermission();
	
					$toSave->setPermissionId( $permissionId );
					$toSave->setRoleId( $value );
	
					$toSave->save();
				}
			}
		}

		return true;
	}

	// Delete -----------

	public static function delete( $permission ) {

		$permission->delete();

		return true;
	}
}

?>