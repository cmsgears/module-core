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

/**
 * The stats stores meta data of tables.
 *
 * @property integer $id
 * @property string $tableName
 * @property string $type
 * @property integer $count
 *
 * @since 1.0.0
 */
class Stats extends \cmsgears\core\common\models\base\Resource {

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
			[ [ 'tableName', 'type', 'count' ], 'required' ],
			[ 'id', 'safe' ],
			// Text Limit
			[ 'type', 'string', 'min' => 0, 'max' => Yii::$app->core->mediumText ],
			[ 'tableName', 'string', 'min' => 0, 'max' => Yii::$app->core->xxLargeText ],
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
			'tableName' => 'Table',
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

	/**
	 * Delete all stats related to given table name.
	 *
	 * @return int the number of rows deleted.
	 */
	public static function deleteByTableName( $tableName ) {

		return self::deleteAll( 'tableName=:tableName', [ ':tableName' => $tableName ] );
	}
}
