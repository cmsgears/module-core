<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\validators\FilterValidator;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\traits\CreateModifyTrait;

/**
 * Role Entity
 *
 * @property integer $id
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $name
 * @property string $slug
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
	public function getPermissionMappingList() {

    	return $this->hasMany( RolePermission::className(), [ 'roleId' => 'id' ] );
	}

	/**
	 * @return array having permission id element.
	 */
	public function getPermissionsIdList() {

    	$permissions 		= $this->permissionMappingList;
		$permissionsList	= array();

		foreach ( $permissions as $permission ) {

			array_push( $permissionsList, $permission->permissionId );
		}

		return $permissionsList;
	}

	/**
	 * @return array having permission name element.
	 */
	public function getPermissionsSlugList() {

    	$permissions 		= $this->permissions;
		$permissionsList	= array();

		foreach ( $permissions as $permission ) {

			array_push( $permissionsList, $permission->slug );
		}

		return $permissionsList;
	}

	// yii\base\Component ----------------

    /**
     * @inheritdoc
     */
    public function behaviors() {

        return [

            'sluggableBehavior' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'slugAttribute' => 'slug',
                'ensureUnique' => true
            ],
            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'createdAt',
 				'updatedAtAttribute' => 'modifiedAt',
 				'value' => new Expression('NOW()')
            ]
        ];
    }

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

		$trim		= [];

		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'name' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];
		}

        $rules = [
            [ [ 'name' ], 'required' ],
            [ [ 'id', 'slug', 'description', 'homeUrl' ], 'safe' ],
            [ 'name', 'alphanumhyphenspace' ],
            [ [ 'name', 'type', 'icon' ], 'string', 'min'=>1, 'max'=>100 ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ [ 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];

		if( Yii::$app->cmgCore->trimFieldValue ) {

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'description' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'homeUrl' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_HOME_URL )
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_ROLE;
	}

	// Role ------------------------------

	/**
	 * @return Role - by slug
	 */
	public static function findBySlug( $slug ) {

		return self::find()->where( 'slug=:slug', [ ':slug' => $slug ] )->one();
	}
}

?>