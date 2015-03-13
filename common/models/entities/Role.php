<?php
namespace cmsgears\modules\core\common\models\entities;

class Role extends NamedActiveRecord {

	// Instance Methods --------------------------------------------

	// db columns

	public function getId() {

		return $this->role_id;
	}

	public function getName() {

		return $this->role_name;
	}

	public function setName( $name ) {

		$this->role_name = $name;
	}

	public function getDesc() {

		return $this->role_desc;
	}

	public function setDesc( $desc ) {

		$this->role_desc = $desc;
	}

	public function getHome() {

		return $this->role_home;
	}

	public function setHome( $home ) {

		$this->role_home = $home;
	}

	public function getPermissions() {

    	return $this->hasMany( Permission::className(), [ 'permission_id' => 'permission_id' ] )
					->viaTable( CoreTables::TABLE_ROLE_PERMISSION, [ 'role_id' => 'role_id' ] );
	}

	public function getPermissionsMap() {

    	return $this->hasMany( RolePermission::className(), [ 'role_id' => 'role_id' ] );
	}

	public function getPermissionsIdList() {

    	$permissions 		= $this->permissionsMap;
		$permissionsList	= array();

		foreach ( $permissions as $permission ) {

			array_push( $permissionsList, $permission->permission_id );
		}

		return $permissionsList;
	}

	public function getPermissionsNameList() {

    	$permissions 		= $this->permissions;
		$permissionsList	= array();

		foreach ( $permissions as $permission ) {

			array_push( $permissionsList, $permission->getName() );
		}

		return $permissionsList;
	}

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'role_name' ], 'required' ],
            [ 'role_name', 'alphanumhyphenspace' ],
            [ 'role_name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'role_name', 'validateNameUpdate', 'on' => [ 'update' ] ],
			[ [ 'role_desc', 'role_home' ], 'safe' ]
        ];
    }

	public function attributeLabels() {

		return [
			'role_name' => 'Role',
			'role_desc' => 'Description',
			'role_home' => 'Home Url'
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

		return Role::find()->where( 'role_id=:roleId', [ ':roleId' => $id ] )->one();
	}

	public static function findByName( $name ) {

		return Role::find()->where( 'role_name=:roleName', [ ':roleName' => $name ] )->one();
	}
}

?>