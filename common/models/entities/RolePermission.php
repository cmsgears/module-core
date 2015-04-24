<?php
namespace cmsgears\core\common\models\entities;

/**
 * RolePermission Entity
 *
 * @property integer $roleId
 * @property integer $permissionId
 */
class RolePermission extends CmgEntity {

	// Instance Methods --------------------------------------------

	/**
	 * @return Role for the mapping.
	 */
	public function getRole() {

		return $this->hasOne( Role::className(), [ 'id' => 'roleId' ] );
	}

	/**
	 * @return Permission for the mapping.
	 */
	public function getPermission() {

		return $this->hasOne( Permission::className(), [ 'id' => 'permissionId' ] );
	}

	// yii\base\Model --------------------

	public function rules() {

        return [
            [ [ 'roleId', 'permissionId' ], 'required' ],
            [ [ 'roleId', 'permissionId' ], 'number', 'integerOnly' => true, 'min' => 1 ]
        ];
    }

	public function attributeLabels() {

		return [
			'roleId' => 'Role',
			'permissionId' => 'Permission'
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	public static function tableName() {

		return CoreTables::TABLE_ROLE_PERMISSION;
	}

	// RolePermission --------------------

	// Delete

	public static function deleteByRoleId( $roleId ) {

		self::deleteAll( 'roleId=:id', [ ':id' => $roleId ] );
	}

	public static function deleteByPermissionId( $permissionId ) {

		self::deleteAll( 'permissionId=:id', [ ':id' => $permissionId ] );
	}
}

?>