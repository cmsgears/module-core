<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\mappers\RolePermission;

use cmsgears\core\common\models\traits\CreateModifyTrait;
use cmsgears\core\common\models\traits\resources\HierarchyTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * Role Entity
 *
 * @property long $id
 * @property long $createdBy
 * @property long $modifiedBy
 * @property string $name
 * @property string $slug
 * @property string $type
 * @property string $icon
 * @property string $description
 * @property string $homeUrl
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 */
class Role extends \cmsgears\core\common\models\base\TypedEntity {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	private $parentType		= CoreGlobal::TYPE_ROLE; // required for traits

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

    use CreateModifyTrait;
	use HierarchyTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

    /**
     * @inheritdoc
     */
    public function behaviors() {

        return [
            'authorBehavior' => [
                'class' => AuthorBehavior::className()
            ],
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

	// yii\base\Model ---------

    /**
     * @inheritdoc
     */
    public function rules() {

        // model rules
        $rules = [
            [ [ 'name' ], 'required' ],
            [ [ 'id' ], 'safe' ],
            [ [ 'name', 'type', 'icon' ], 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->mediumText ],
            [ 'slug', 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->largeText ],
            [ [ 'description', 'homeUrl' ], 'string', 'min' => 0, 'max' => Yii::$app->cmgCore->extraLargeText ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ [ 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];

        // trim if required
        if( Yii::$app->cmgCore->trimFieldValue ) {

            $trim[] = [ [ 'name', 'homeUrl' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

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
            'type' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
            'icon' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ICON ),
            'description' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
            'homeUrl' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_HOME_URL )
        ];
    }

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Role ----------------------------------

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

        $permissions        = $this->permissionMappingList;
        $permissionsList    = array();

        foreach ( $permissions as $permission ) {

            array_push( $permissionsList, $permission->permissionId );
        }

        return $permissionsList;
    }

    /**
     * @return array having permission name element.
     */
    public function getPermissionsSlugList( $level = 0 ) {

		$slugList	= [];
		$idList		= [];

		// Generate L0 Slugs and Ids List
		if( $level <= 1 ) {

			$permissions	= $this->permissions;

	        foreach ( $permissions as $permission ) {

	            array_push( $slugList, $permission->slug );
				array_push( $idList, $permission->id );
	        }
		}

		// Add L1 slugs to L0 slugs
		if( $level == 1 ) {

			$permissions	= Permission::getChildrenForL0( $idList );

	        foreach ( $permissions as $permission ) {

	            array_push( $slugList, $permission->slug );
	        }
		}

        return $slugList;
    }

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_ROLE;
    }

	// CMG parent classes --------------------

	// Role ----------------------------------

	// Read - Query -----------

	// Read - Find ------------

    /**
     * @return Role - by slug
     */
    public static function findBySlug( $slug ) {

        return self::find()->where( 'slug=:slug', [ ':slug' => $slug ] )->one();
    }

	// Create -----------------

	// Update -----------------

	// Delete -----------------
}

?>