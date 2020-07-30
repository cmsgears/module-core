<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\mappers;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;

/**
 * Mapper to map source model to target model.
 *
 * @property integer $sourceId
 * @property string $sourceType
 * @property integer $targetId
 * @property string $targetType
 * @property boolean $active
 * @property integer $order
 *
 * @since 1.0.0
 */
class Dependency extends \cmsgears\core\common\models\base\Mapper {

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
			[ [ 'sourceId', 'targetId', 'sourceType', 'targetType' ], 'required' ],
			// Unique
			[ [ 'sourceId', 'targetId', 'sourceType', 'targetType' ], 'unique', 'targetAttribute' => [ 'sourceId', 'targetId', 'sourceType', 'targetType' ], 'comboNotUnique' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_EXIST ) ],
			// Text Limit
			[ [ 'sourceType', 'targetType' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			// Other
			[ [ 'sourceId', 'targetId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ 'active', 'boolean' ],
			[ 'order', 'number', 'integerOnly' => true, 'min' => 0 ]
		];

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'sourceId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SOURCE ),
			'sourceType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SOURCE_TYPE ),
			'targetId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TARGET ),
			'targetType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TARGET_TYPE ),
			'active' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ACTIVE ),
			'order' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ORDER )
		];
	}

	// yii\db\BaseActiveRecord

    /**
     * @inheritdoc
     */
	public function beforeSave( $insert ) {

	    if( parent::beforeSave( $insert ) ) {

			if( empty( $this->order ) || $this->order <= 0 ) {

				$this->order = 0;
			}

	        return true;
	    }

		return false;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Dependency ----------------------------

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_DEPENDENCY );
	}

	// CMG parent classes --------------------

	// Dependency ----------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
