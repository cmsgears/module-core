<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Permission;

class PermissionService extends Service {

	// Static Methods ----------------------------------------------
	
	// Read ----------------

	public static function findById( $id ) {

		return Permission::findOne( $id );
	}

	public static function getIdNameList() {

		return self::findIdNameList( 'id', 'name', CoreTables::TABLE_PERMISSION );
	}
}

?>