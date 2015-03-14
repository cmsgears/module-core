<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Permission;

use cmsgears\core\common\services\Service;

class PermissionService extends Service {

	// Static Methods ----------------------------------------------
	
	// Read ----------------

	public static function findById( $id ) {

		return Permission::findOne( $id );
	}

	public static function getIdNameList() {

		return self::findIdNameList( 'permission_id', 'permission_name', CoreTables::TABLE_PERMISSION );
	}
}

?>