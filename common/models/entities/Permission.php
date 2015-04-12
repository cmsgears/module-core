<?php
namespace cmsgears\core\common\models\entities;

/**
 * Permission Entity
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
class Permission extends NamedActiveRecord {

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
	 * @return Role array
	 */
	public function getRoles() {
	
    	return $this->hasMany( Role::className(), [ 'id' => 'roleId' ] )
					->viaTable( CoreTables::TABLE_ROLE_PERMISSION, [ 'permissionId' => 'id' ] );
	}

	/**
	 * @return array having role element.
	 */
	public function getRolesList() {

    	return $this->hasMany( RolePermission::className(), [ 'permissionId' => 'id' ] );
	}

	/**
	 * @return array having role id element.
	 */
	public function getRolesIdList() {

    	$roles 		= $this->rolesList;
		$rolesList	= array();
		
		foreach ( $roles as $role ) {
			
			array_push( $rolesList, $role->roleId );
		}

		return $rolesList;
	}

	// yii\base\Model --------------------

	public function rules() {

        return [
            [ [ 'name' ], 'required' ],
            [ [ 'id', 'description' ], 'safe' ],
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
			'name' => 'Permission',
			'description' => 'Description'
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------
	
	public static function tableName() {
		
		return CoreTables::TABLE_PERMISSION;
	}

	// Permission ------------------------

}

?>