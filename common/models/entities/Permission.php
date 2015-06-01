<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\traits\CreateModifyTrait;

/**
 * Permission Entity
 *
 * @property integer $id
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $name
 * @property string $description
 * @property string $icon 
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 */
class Permission extends NamedCmgEntity {

	use CreateModifyTrait;

	// Instance Methods --------------------------------------------

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

	// yii\base\Component ----------------

    /**
     * @inheritdoc
     */
    public function behaviors() {

        return [

            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'createdAt',
 				'updatedAtAttribute' => 'modifiedAt'
            ]
        ];
    }

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

        return [
            [ [ 'name' ], 'required' ],
            [ [ 'id', 'description' ], 'safe' ],
            [ 'name', 'alphanumhyphenspace' ],
            [ [ 'name', 'icon' ], 'string', 'min'=>1, 'max'=>100 ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ [ 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'description' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION )
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_PERMISSION;
	}

	// Permission ------------------------

}

?>