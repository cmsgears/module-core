<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\base;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Base model of all the mapper mapping parent model to model being mapped.
 *
 * The parent model can be either entity or resource. Similarly the model being mapped
 * can be both entity or resource.
 *
 * @property integer $modelId Id of model being mapped.
 * @property integer $parentId Id of parent model.
 * @property integer $parentType Type of parent model.
 * @property string $type Mapper type to group the mappings.
 * @property integer $order Mapping order.
 * @property boolean $active Flag to check whether mapping is still active.
 *
 * @since 1.0.0
 */
abstract class ModelMapper extends Mapper {

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

		// Model Rules
		$rules = [
			// Required, Safe
			[ [ 'modelId', 'parentId', 'parentType' ], 'required' ],
			[ 'id', 'safe' ],
			// Unique
			[ [ 'modelId', 'parentId', 'parentType' ], 'unique', 'targetAttribute' => [ 'modelId', 'parentId', 'parentType' ], 'comboNotUnique' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_EXIST ) ],
			// Text Limit
			[ [ 'parentType', 'type' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			// Other
			[ 'modelId', 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
			[ 'parentId', 'number', 'integerOnly' => true, 'min' => 1 ],
			[ 'order', 'number', 'integerOnly' => true, 'min' => 0 ],
			[ 'active', 'boolean' ]
		];

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'modelId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ADDRESS ),
			'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ADDRESS_TYPE ),
			'order' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
			'active' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ACTIVE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// ModelMapper ---------------------------

	/**
	 * Returns primary model of the mapper.
	 *
	 * @return ActiveRecord Primary model.
	 */
	abstract public function getModel();

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	// CMG parent classes --------------------

	// ModelMapper ---------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'model' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	/**
	 * Find and return appropriate mapped models for given parent id, parent type and type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $type
	 * @return Mapper[] by parent id, parent type and type
	 */
	public static function findByType( $parentId, $parentType, $type ) {

		return self::queryByParent( $parentId, $parentType )->andWhere( 'type=:type', [ ':type' => $type ] )->all();
	}

	/**
	 * Find and return appropriate mapped model for given parent id, parent type and type.
	 * It's useful in cases where only one mapping is allowed for a model for given type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $type
	 * @return Mapper by parent id, parent type and type
	 */
	public static function findFirstByType( $parentId, $parentType, $type ) {

		return self::queryByParent( $parentId, $parentType )->andWhere( 'type=:type', [ ':type' => $type ] )->one();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
