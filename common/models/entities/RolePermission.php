<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use yii\db\ActiveRecord;

class RolePermission extends ActiveRecord {

	// Instance Methods --------------------------------------------

	public function getRole() {

		return $this->hasOne( Role::className(), [ 'id' => 'roleId' ] );
	}

	public function getPermission() {

		return $this->hasOne( Permission::className(), [ 'id' => 'permissionId' ] );
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord

	public static function tableName() {

		return CoreTables::TABLE_ROLE_PERMISSION;
	}

	// RolePermission

	// Delete --------

	public static function deleteByRoleId( $roleId ) {

		self::deleteAll( 'roleId=:id', [ ':id' => $roleId ] );
	}

	public static function deleteByPermissionId( $permissionId ) {

		self::deleteAll( 'permissionId=:id', [ ':id' => $permissionId ] );
	}
}

?>