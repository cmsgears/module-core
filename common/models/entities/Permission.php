<?php
namespace cmsgears\modules\core\common\models\entities;

class Permission extends NamedActiveRecord {

	// Site Module
	const PERM_ADMIN				= "admin"; 	// Allows to view Admin Site Home
	const PERM_USER					= "user"; 	// Allows to view User Site Home

	// Settings
	const PERM_SETTINGS				= "settings";

	// User Module
	const PERM_IDENTITY				= "identity";
	const PERM_IDENTITY_USER		= "identity-user";
	const PERM_RBAC					= "identity-rbac";

	// Newsletter
	const PERM_NEWSLETTER			= "newsletter";

	// Category Module
	const PERM_CATEGORY				= "category";

	// Instance Methods --------------------------------------------

	// db columns

	public function getId() {

		return $this->permission_id;
	}

	public function getName() {

		return $this->permission_name;
	}

	public function setName( $name ) {

		$this->permission_name = $name;
	}

	public function getDesc() {

		return $this->permission_desc;
	}

	public function setDesc( $desc ) {

		$this->permission_desc = $desc;
	}

	public function getRoles() {
	
    	return $this->hasMany( Role::className(), [ 'role_id' => 'role_id' ] )
					->viaTable( CoreTables::TABLE_ROLE_PERMISSION, [ 'permission_id' => 'permission_id' ] );
	}

	public function getRolesMap() {
	
    	return $this->hasMany( RolePermission::className(), [ 'permission_id' => 'permission_id' ] );
	}

	public function getRolesIdList() {

    	$roles 		= $this->rolesMap;
		$rolesList	= array();
		
		foreach ( $roles as $role ) {
			
			array_push( $rolesList, $role->role_id );
		}

		return $rolesList;
	}

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'permission_name' ], 'required' ],
            [ 'permission_name', 'alphanumhyphenspace' ],
            [ 'permission_name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'permission_name', 'validateNameUpdate', 'on' => [ 'update' ] ],
			[ [ 'permission_desc' ], 'safe' ]
        ];
    }

	public function attributeLabels() {

		return [
			'permission_name' => 'Permission',
			'permission_desc' => 'Description'
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord
	
	public static function tableName() {
		
		return CoreTables::TABLE_PERMISSION;
	}

	// Permission

	// Read --------

	public static function findById( $id ) {

		return Permission::find()->where( 'permission_id=:id', [ ':id' => $id ] )->one();
	}

	public static function findByName( $name ) {

		return Permission::find()->where( 'permission_name=:name', [ ':name' => $name ] )->one();
	}
}

?>