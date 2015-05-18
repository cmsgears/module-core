<?php
namespace cmsgears\core\common\models\entities;

use cmsgears\core\common\models\traits\CreateModifyTrait;

/**
 * Role Entity
 *
 * @property integer $id
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $name
 * @property string $description
 * @property string $homeUrl
 * @property string $type
 * @property string $icon
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 */
class Role extends NamedCmgEntity {

	use CreateModifyTrait;

	// Instance Methods --------------------------------------------

	/**
	 * @return array - Permission
	 */
	public function getPermissions() {

    	return $this->hasMany( Permission::className(), [ 'id' => 'permissionId' ] )
					->viaTable( CoreTables::TABLE_ROLE_PERMISSION, [ 'roleId' => 'id' ] );
	}

	/**
	 * @return array - having permissions list from the joining table
	 */
	public function getPermissionsList() {

    	return $this->hasMany( RolePermission::className(), [ 'roleId' => 'id' ] );
	}

	/**
	 * @return array having permission id element.
	 */
	public function getPermissionsIdList() {

    	$permissions 		= $this->permissionsList;
		$permissionsList	= array();

		foreach ( $permissions as $permission ) {

			array_push( $permissionsList, $permission->permissionId );
		}

		return $permissionsList;
	}

	/**
	 * @return array having permission name element.
	 */
	public function getPermissionsNameList() {

    	$permissions 		= $this->permissions;
		$permissionsList	= array();

		foreach ( $permissions as $permission ) {

			array_push( $permissionsList, $permission->name );
		}

		return $permissionsList;
	}

	// yii\base\Model --------------------

	/**
	 * Validation rules
	 */
	public function rules() {

        return [
            [ [ 'name' ], 'required' ],
            [ [ 'id', 'description', 'homeUrl' ], 'safe' ],
            [ 'name', 'alphanumhyphenspace' ],
            [ [ 'name', 'type', 'icon' ], 'string', 'min'=>1, 'max'=>100 ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ [ 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => 'yyyy-MM-dd HH:mm:ss' ]
        ];
    }

	/**
	 * Model attributes
	 */
	public function attributeLabels() {

		return [
			'name' => 'Role',
			'description' => 'Description',
			'homeUrl' => 'Home Url'
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	/**
	 * @return string - db table name
	 */
	public static function tableName() {

		return CoreTables::TABLE_ROLE;
	}

	// Role ------------------------------

}

?>