<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Permission;

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

		return Permission::findOne( $id );
	}

	/**
	 * @param string $id
	 * @return array - An array of associative array of permission id and name
	 */
	public static function getIdNameList() {

		return self::findIdNameList( 'id', 'name', CoreTables::TABLE_PERMISSION );
	}
}

?>