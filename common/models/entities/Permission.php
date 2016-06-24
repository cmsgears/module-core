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
 * Permission Entity
 *
 * @property long $id
 * @property long $createdBy
 * @property long $modifiedBy
 * @property string $name
 * @property string $slug
 * @property string $type
 * @property string $icon
 * @property string $description
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 */
class Permission extends \cmsgears\core\common\models\base\TypedEntity {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	private $parentType		= CoreGlobal::TYPE_PERMISSION; // required for traits

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
            [ [ 'description' ], 'string', 'min' => 0, 'max' => Yii::$app->cmgCore->xLargeText ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ [ 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];

        // trim if required
        if( Yii::$app->cmgCore->trimFieldValue ) {

            $trim[] = [ [ 'name' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

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
            'description' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION )
        ];
    }

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Permission ----------------------------

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
    public function getRoleMappingList() {

        return $this->hasMany( RolePermission::className(), [ 'permissionId' => 'id' ] );
    }

    /**
     * @return array having role id element.
     */
    public function getRolesIdList() {

        $roles      = $this->roleMappingList;
        $rolesList  = array();

        foreach ( $roles as $role ) {

            array_push( $rolesList, $role->roleId );
        }

        return $rolesList;
    }

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_PERMISSION;
    }

	// CMG parent classes --------------------

	// Permission ----------------------------

	// Read - Query -----------

	// Read - Find ------------

    /**
     * @return Permission - by slug
     */
    public static function findBySlug( $slug ) {

        return self::find()->where( 'slug=:slug', [ ':slug' => $slug ] )->one();
    }

    public static function getChildrenForL0( $l0Ids = [] ) {

		$permission 	= CoreTables::TABLE_PERMISSION;
		$modelHierarchy = CoreTables::TABLE_MODEL_HIERARCHY;
		$l0Ids			= join( ",", $l0Ids );

    	return Permission::find()->leftJoin( $modelHierarchy, "`$permission`.`id` = `$modelHierarchy`.`childId`" )
    					  ->where( "`$modelHierarchy`.`parentType` = 'permission' AND `$modelHierarchy`.`parentId` IN ($l0Ids)" )->all();
    }

	// Create -----------------

	// Update -----------------

	// Delete -----------------
}

?>