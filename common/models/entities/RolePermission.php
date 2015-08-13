<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * RolePermission Entity
 *
 * @property integer $roleId
 * @property integer $permissionId
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
     * @inheritdoc
     */
	public function rules() {

        return [
            [ [ 'roleId', 'permissionId' ], 'required' ],
            [ [ 'roleId', 'permissionId' ], 'number', 'integerOnly' => true, 'min' => 1 ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'roleId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ROLE ),
			'permissionId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PERMISSION )
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_ROLE_PERMISSION;
	}

	// RolePermission --------------------

	// Delete ----

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