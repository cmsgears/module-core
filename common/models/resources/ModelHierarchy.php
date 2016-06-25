<?php
namespace cmsgears\core\common\models\resources;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\models\traits\ParentTypeTrait;

/**
 * ModelHierarchy Entity
 *
 * @property integer $id
 * @property integer $parentId
 * @property integer $childId
 * @property integer $rootId
 * @property string $parentType
 * @property string $lValue
 * @property short $rValue
 */
class ModelHierarchy extends \cmsgears\core\common\models\base\Resource {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use ParentTypeTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Model ---------

    /**
     * @inheritdoc
     */
	public function rules() {

        return [
            [ [ 'rootId', 'parentType' ], 'required' ],
            [ [ 'id' ], 'safe' ],
            [ [ 'parentType' ], 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->mediumText ],
            [ [ 'parentId', 'childId', 'rootId', 'lValue', 'rValue' ], 'number', 'integerOnly' => true, 'min' => 1 ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// ModelHierarchy ------------------------

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_MODEL_HIERARCHY;
	}

	// CMG parent classes --------------------

	// ModelHierarchy ------------------------

	// Read - Query -----------

	// Read - Find ------------

    public static function findRoot( $rootId, $parentType ) {

        return self::find()->where( 'rootId=:rid AND parentId=NULL parentType=:type', [ ':rid' => $rootId, ':type' => $parentType ] )->one();
    }

	// Create -----------------

	// Update -----------------

	// Delete -----------------
}

?>