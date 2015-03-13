<?php
namespace cmsgears\modules\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\modules\core\common\models\entities\CoreTables;
use cmsgears\modules\core\common\models\entities\Permission;

use cmsgears\modules\core\common\services\Service;

class PermissionService extends Service {

	// Static Methods ----------------------------------------------
	
	// Read ----------------

	public static function findById( $id ) {

		return Permission::findOne( $id );
	}

	public static function getIdNameArrayList() {

		return self::findIdNameArrayList( 'permission_id', 'permission_name', CoreTables::TABLE_PERMISSION );
	}
}

?>