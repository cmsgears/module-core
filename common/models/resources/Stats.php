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

use cmsgears\core\common\models\base\Resource;

/**
 * The stats stores meta data of tables.
 *
 * @property integer $id
 * @property string $table
 * @property string $type
 * @property integer $count
 *
 * @since 1.0.0
 */
class Stats extends Resource {

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
			[ [ 'table', 'type', 'count' ], 'required' ],
			[ 'id', 'safe' ],
			// Text Limit
			[ 'type', 'string', 'min' => 0, 'max' => Yii::$app->core->mediumText ],
			[ 'table', 'string', 'min' => 0, 'max' => Yii::$app->core->xxLargeText ],
			// Others
			[ 'rows' => 'number', 'integerOnly' => true ]
		];

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'table' => 'Table',
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'count' => 'Count'
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Stats ---------------------------------

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_STATS );
	}

	// CMG parent classes --------------------

	// Stats ---------------------------------

	// Read - Query -----------

	// Read - Find ------------

	/**
	 * Returns row count for given table and type.
	 *
	 * @param string $table
	 * @param string $type
	 * @return integer
	 */
	public static function getRowCount( $table, $type = 'row' ) {

		$stat = self::find()->where( '`table`=:table AND type=:type', [ ':table' => $table, ':type' => $type ] )->one();

		if( isset( $stat ) ) {

			return $stat->count;
		}

		return 0;
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
