<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Role;
use cmsgears\core\common\models\entities\RolePermission;

use cmsgears\core\common\services\Service;

class RoleService extends Service {

	// Static Methods ----------------------------------------------
	
	// Read ----------------

	public static function findById( $id ) {

		return Role::findById( $id );
	}

	public static function findByName( $name ) {

		return Role::findByName( $name );
	}

	public static function getIdNameList() {

		return self::findIdNameList( 'id', 'name', CoreTables::TABLE_ROLE );
	}
}

?>