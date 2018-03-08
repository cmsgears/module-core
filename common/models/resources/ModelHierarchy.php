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

use cmsgears\core\common\models\interfaces\base\IModelResource;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\base\ModelResource;

use cmsgears\core\common\models\traits\base\ModelResourceTrait;

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
class ModelHierarchy extends ModelResource implements IModelResource {

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

	use ModelResourceTrait;

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

		// Model Rules
		$rules = [
			// Required, Safe
			[ [ 'rootId', 'parentType' ], 'required' ],
			[ 'id', 'safe' ],
			// Text Limit
			[ 'parentType', 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			// Other
			[ [ 'parentId', 'childId', 'rootId', 'lValue', 'rValue' ], 'number', 'integerOnly' => true, 'min' => 1 ]
		];

		return $rules;
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

	/**
	 * Find and return the top level model using given root id for parent type.
	 *
	 * @param integer $rootId
	 * @param string $parentType
	 * @return ModelHierarchy
	 */
	public static function findRoot( $rootId, $parentType ) {

		return self::find()->where( 'rootId=:rid AND parentType=:type AND parentId IS NULL', [ ':rid' => $rootId, ':type' => $parentType ] )->one();
	}

	/**
	 * Find and return the child using parent id, parent type and child id.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param integer $childId
	 * @return ModelHierarchy
	 */
	public static function findChild( $parentId, $parentType, $childId ) {

		return self::find()->where( 'parentId=:pid AND parentType=:type AND childId=:cid', [ ':pid' => $parentId, ':type' => $parentType, ':cid' => $childId ] )->one();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

	/**
	 * Delete entire hierarchy for given root id and parent type.
	 *
	 * @param integer $rootId
	 * @param string $parentType
	 * @return integer Number of rows deleted.
	 */
	public static function deleteByRootId( $rootId, $parentType ) {

		return self::deleteAll( 'rootId=:rid AND parentType=:type', [ ':rid' => $rootId, ':type' => $parentType ] );
	}
}
