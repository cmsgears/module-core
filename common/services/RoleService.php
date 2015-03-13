<?php
namespace cmsgears\modules\core\common\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\modules\core\common\models\entities\CoreTables;
use cmsgears\modules\core\common\models\entities\Role;
use cmsgears\modules\core\common\models\entities\RolePermission;

use cmsgears\modules\core\common\services\Service;

class RoleService extends Service {

	// Static Methods ----------------------------------------------
	
	// Read ----------------

	public static function findById( $id ) {
		
		return Role::findById( $id );
	}

	public static function findByName( $name ) {
		
		return Role::findByName( $name );
	}

	public static function getIdNameArrayList() {

		return self::findIdNameArrayList( 'role_id', 'role_name', CoreTables::TABLE_ROLE );
	}
}

?>