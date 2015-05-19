<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Role;

class RoleService extends Service {

	// Static Methods ----------------------------------------------
	
	// Read ----------------

	public static function findById( $id ) {

		return Role::findById( $id );
	}

	public static function findByName( $name ) {

		return Role::findByName( $name );
	}

	public static function getIdNameMap() {

		return self::findMap( 'id', 'name', CoreTables::TABLE_ROLE );
	}

	public static function getIdNameList() {

		return self::findIdNameList( 'id', 'name', CoreTables::TABLE_ROLE );
	}
}

?>