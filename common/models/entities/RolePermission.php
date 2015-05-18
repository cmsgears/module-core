<?php
namespace cmsgears\core\common\models\entities;

/**
 * RolePermission Entity
 *
 * @property int $roleId
 * @property int $permissionId
 */
class RolePermission extends CmgEntity {

	// Instance Methods --------------------------------------------

	/**
	 * @return Role - from the mapping.
	 */
	public function getRole() {

		return $this->hasOne( Role::className(), [ 'id' => 'roleId' ] );
	}

	/**
	 * @return Permission - from the mapping.
	 */
	public function getPermission() {

		return $this->hasOne( Permission::className(), [ 'id' => 'permissionId' ] );
	}

	// yii\base\Model --------------------

	/**
	 * Validation rules
	 */
	public function rules() {

        return [
            [ [ 'roleId', 'permissionId' ], 'required' ],
            [ [ 'roleId', 'permissionId' ], 'number', 'integerOnly' => true, 'min' => 1 ]
        ];
    }

	/**
	 * Model attributes
	 */
	public function attributeLabels() {

		return [
			'roleId' => 'Role',
			'permissionId' => 'Permission'
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	/**
	 * @return string - db table name
	 */
	public static function tableName() {

		return CoreTables::TABLE_ROLE_PERMISSION;
	}

	// RolePermission --------------------

	// Delete

	/**
	 * Delete the mappings by given role id.
	 */
	public static function deleteByRoleId( $roleId ) {

		self::deleteAll( 'roleId=:id', [ ':id' => $roleId ] );
	}

	/**
	 * Delete the mappings by given permission id.
	 */
	public static function deleteByPermissionId( $permissionId ) {

		self::deleteAll( 'permissionId=:id', [ ':id' => $permissionId ] );
	}
}

?>