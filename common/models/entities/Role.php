<?php
namespace cmsgears\core\common\models\entities;

class Role extends NamedActiveRecord {

	// Instance Methods --------------------------------------------

	public function getCreator() {

		return $this->hasOne( User::className(), [ 'id' => 'createdBy' ] );
	}

	public function getModifier() {

		return $this->hasOne( User::className(), [ 'id' => 'modifiedBy' ] );
	}

	public function getPermissions() {

    	return $this->hasMany( Permission::className(), [ 'id' => 'permissionId' ] )
					->viaTable( CoreTables::TABLE_ROLE_PERMISSION, [ 'roleId' => 'id' ] );
	}

	public function getPermissionsMap() {

    	return $this->hasMany( RolePermission::className(), [ 'roleId' => 'id' ] );
	}

	public function getPermissionsIdList() {

    	$permissions 		= $this->permissionsMap;
		$permissionsList	= array();

		foreach ( $permissions as $permission ) {

			array_push( $permissionsList, $permission->permissionId );
		}

		return $permissionsList;
	}

	public function getPermissionsNameList() {

    	$permissions 		= $this->permissions;
		$permissionsList	= array();

		foreach ( $permissions as $permission ) {

			array_push( $permissionsList, $permission->name );
		}

		return $permissionsList;
	}

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'name' ], 'required' ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
			[ [ 'description', 'homeUrl', 'createdBy', 'modifiedBy' ], 'safe' ]
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

	// yii\db\ActiveRecord

	public static function tableName() {

		return CoreTables::TABLE_ROLE;
	}

	// Role

	// Read --------

	public static function findById( $id ) {

		return Role::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}

	public static function findByName( $name ) {

		return Role::find()->where( 'name=:name', [ ':name' => $name ] )->one();
	}
}

?>