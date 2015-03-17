<?php
namespace cmsgears\core\common\models\entities;

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

	// Slider
	const PERM_SLIDER				= "slider";

	// Category
	const PERM_CATEGORY				= "category";

	// Instance Methods --------------------------------------------

	// db columns

	public function getCreator() {

		return $this->hasOne( User::className(), [ 'id' => 'createdBy' ] );
	}

	public function getModifier() {

		return $this->hasOne( User::className(), [ 'id' => 'modifiedBy' ] );
	}

	public function getRoles() {
	
    	return $this->hasMany( Role::className(), [ 'id' => 'roleId' ] )
					->viaTable( CoreTables::TABLE_ROLE_PERMISSION, [ 'permissionId' => 'id' ] );
	}

	public function getRolesMap() {
	
    	return $this->hasMany( RolePermission::className(), [ 'permissionId' => 'id' ] );
	}

	public function getRolesIdList() {

    	$roles 		= $this->rolesMap;
		$rolesList	= array();
		
		foreach ( $roles as $role ) {
			
			array_push( $rolesList, $role->roleId );
		}

		return $rolesList;
	}

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'name' ], 'required' ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
			[ [ 'description', 'createdBy', 'modifiedBy' ], 'safe' ]
        ];
    }

	public function attributeLabels() {

		return [
			'name' => 'Permission',
			'description' => 'Description'
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

		return Permission::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}

	public static function findByName( $name ) {

		return Permission::find()->where( 'name=:name', [ ':name' => $name ] )->one();
	}
}

?>