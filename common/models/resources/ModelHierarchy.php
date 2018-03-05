<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\resources;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\models\traits\ResourceTrait;

/**
 * ModelHierarchy Entity
 *
 * @property integer $id
 * @property integer $parentId
 * @property integer $childId
 * @property integer $rootId
 * @property string $parentType
 * @property string $lValue
 * @property integer $rValue
 *
 * @since 1.0.0
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

	use ResourceTrait;

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
			// Required, Safe
			[ [ 'rootId', 'parentType' ], 'required' ],
			[ [ 'id' ], 'safe' ],
			// Text Limit
			[ 'parentType', 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			// Other
			[ [ 'parentId', 'childId', 'rootId', 'lValue', 'rValue' ], 'number', 'integerOnly' => true, 'min' => 1 ]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE )
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

		return CoreTables::getTableName( CoreTables::TABLE_MODEL_HIERARCHY );
	}

	// CMG parent classes --------------------

	// ModelHierarchy ------------------------

	// Read - Query -----------

	// Read - Find ------------

	public static function findRoot( $rootId, $parentType ) {

		return self::find()->where( 'rootId=:rid AND parentType=:type AND parentId IS NULL', [ ':rid' => $rootId, ':type' => $parentType ] )->one();
	}

	public static function findChild( $parentId, $parentType, $childId ) {

		return self::find()->where( 'parentId=:pid AND parentType=:type AND childId=:cid', [ ':pid' => $parentId, ':type' => $parentType, ':cid' => $childId ] )->one();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

	public static function deleteByRootId( $rootId, $parentType ) {

		return self::deleteAll( 'rootId=:rid AND parentType=:type', [ ':rid' => $rootId, ':type' => $parentType ] );
	}
}
