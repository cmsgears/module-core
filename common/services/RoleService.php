<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Role;

/**
 * The class RoleService is base class to perform database activities for Role Entity.
 */
class RoleService extends Service {

	// Static Methods ----------------------------------------------
	
	// Read ----------------

	/**
	 * @param integer $id
	 * @return Role
	 */
	public static function findById( $id ) {

		return Role::findById( $id );
	}

	/**
	 * @param string $name
	 * @return Role
	 */
	public static function findByName( $name ) {

		return Role::findByName( $name );
	}

	/**
	 * @return array - an array having id as key and name as value.
	 */
	public static function getIdNameMap() {

		return self::findMap( 'id', 'name', CoreTables::TABLE_ROLE );
	}

	/**
	 * @param string $id
	 * @return array - An array of associative array of role id and name.
	 */
	public static function getIdNameList() {

		return self::findIdNameList( 'id', 'name', CoreTables::TABLE_ROLE );
	}
}

?>