<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use yii\db\ActiveRecord;

class RolePermission extends ActiveRecord {

	// Instance Methods --------------------------------------------

	// db columns

	public function getRoleId() {

		return $this->role_id;
	}

	public function getRole() {

		return $this->hasOne( Role::className(), [ 'role_id' => 'role_id' ] );
	}

	public function setRoleId( $roleId ) {

		$this->role_id = $roleId;
	}

	public function getPermissionId() {

		return $this->permission_id;
	}

	public function getPermission() {

		return $this->hasOne( Permission::className(), [ 'permission_id' => 'permission_id' ] );
	}

	public function setPermissionId( $permissionId ) {

		$this->permission_id = $permissionId;
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord

	public static function tableName() {

		return CoreTables::TABLE_ROLE_PERMISSION;
	}

	// RolePermission

	// Delete --------

	public static function deleteByRoleId( $roleId ) {

		self::deleteAll( 'role_id=:id', [ ':id' => $roleId ] );
	}

	public static function deleteByPermissionId( $permissionId ) {

		self::deleteAll( 'permission_id=:id', [ ':id' => $permissionId ] );
	}
}

?>