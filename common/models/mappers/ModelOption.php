<?php
namespace cmsgears\core\common\models\mappers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Option;

/**
 * ModelOption Entity
 *
 * @property integer $id
 * @property integer $optionId
 * @property integer $parentId
 * @property string $parentType
 * @property short $order
 * @property short $active
 */
class ModelOption extends \cmsgears\core\common\models\base\ParentMapper {

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
            [ [ 'optionId', 'parentId', 'parentType' ], 'required' ],
            [ [ 'id', 'active' ], 'safe' ],
            [ 'parentType', 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->mediumText ],
            [ 'order', 'number', 'integerOnly' => true, 'min' => 0 ],
            [ [ 'active' ], 'boolean' ],
            [ [ 'optionId' ], 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
			[ [ 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'optionId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_OPTION ),
			'order' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
			'active' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ACTIVE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// ModelOption ---------------------------

	/**
	 * @return Option - associated option
	 */
	public function getOption() {

    	return $this->hasOne( Option::className(), [ 'id' => 'optionId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_MODEL_OPTION;
	}

	// CMG parent classes --------------------

	// ModelOption ---------------------------

	// Read - Query -----------

	// Read - Find ------------

	public static function findByOptionId( $parentId, $parentType, $optionId ) {

		return self::find()->where( 'parentId=:pid AND parentType=:ptype AND optionId=:cid', [ ':pid' => $parentId, ':ptype' => $parentType, ':cid' => $optionId ] )->one();
	}

	public static function findActiveByParentId( $parentId ) {

		return self::find()->where( 'parentId=:pid AND active=1', [ ':pid' => $parentId ] )->all();
	}

	public static function findActiveByOptionIdParentType( $optionId, $parentType ) {

		return self::find()->where( 'optionId=:cid AND parentType=:ptype AND active=1', [ ':cid' => $optionId, ':ptype' => $parentType ] )->all();
	}

	public static function findActiveByParent( $parentId, $parentType ) {

		return self::find()->where( 'parentId=:pid AND parentType=:ptype AND active=1', [ ':pid' => $parentId, ':ptype' => $parentType ] )->all();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

	/**
	 * Delete all entries related to a option
	 */
	public static function deleteByOptionId( $optionId ) {

		self::deleteAll( 'optionId=:id', [ ':id' => $optionId ] );
	}
}

?>