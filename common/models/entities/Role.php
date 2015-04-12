<?php
namespace cmsgears\core\common\models\entities;

/**
 * Role Entity
 *
 * @property integer $id
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $name
 * @property string $description
 * @property string $homeUrl
 * @property short $type
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 */
class Role extends NamedActiveRecord {

	// Instance Methods --------------------------------------------
	
	/**
	 * @return User
	 */
	public function getCreator() {

		return $this->hasOne( User::className(), [ 'id' => 'createdBy' ] );
	}

	/**
	 * @return User
	 */
	public function getModifier() {

		return $this->hasOne( User::className(), [ 'id' => 'modifiedBy' ] );
	}

	/**
	 * @return Permission array
	 */
	public function getPermissions() {

    	return $this->hasMany( Permission::className(), [ 'id' => 'permissionId' ] )
					->viaTable( CoreTables::TABLE_ROLE_PERMISSION, [ 'roleId' => 'id' ] );
	}

	/**
	 * @return array having permission element.
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

	public function rules() {

        return [
            [ [ 'name' ], 'required' ],
            [ [ 'id', 'description', 'homeUrl' ], 'safe' ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'length', 'min'=>1, 'max'=>100 ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ [ 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => 'yyyy-MM-dd HH:mm:ss' ]
        ];
    }

	public function attributeLabels() {

		return [
			'name' => 'Role',
			'description' => 'Description',
			'homeUrl' => 'Home Url'
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	public static function tableName() {

		return CoreTables::TABLE_ROLE;
	}

	// Role ------------------------------

}

?>