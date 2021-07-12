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
 * @property integer $parentId
 * @property string $parentType
 * @property string $name
 * @property string $type
 * @property integer $count
 *
 * @since 1.0.0
 */
class ModelStats extends \cmsgears\core\common\models\base\ModelResource {

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
			[ [ 'parentId', 'parentType', 'name', 'type', 'count' ], 'required' ],
			[ 'id', 'safe' ],
			// Text Limit
			[ [ 'type', 'parentType' ], 'string', 'min' => 0, 'max' => Yii::$app->core->mediumText ],
			[ 'name', 'string', 'min' => 0, 'max' => Yii::$app->core->xxLargeText ],
			// Others
			[ 'count', 'number', 'integerOnly' => true, 'min' => 0 ],
			[ 'parentId', 'number', 'integerOnly' => true, 'min' => 1 ]
		];

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'count' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_COUNT )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// ModelStats ----------------------------

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_MODEL_STATS );
	}

	// CMG parent classes --------------------

	// ModelStats ----------------------------

	// Read - Query -----------

	// Read - Find ------------

	/**
	 * Returns row count for given table and type.
	 *
	 * @param string $table
	 * @param string $type
	 * @return integer
	 */
	public static function getTableRowCount( $table, $type = 'row', $config = [] ) {

		$siteId = isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

		$ptype = CoreGlobal::TYPE_SITE;

		$stat = self::find()->where( 'parentId=:pid AND parentType=:ptype AND name=:table AND type=:type', [ ':pid' => $siteId, ':ptype' =>  $ptype, ':table' => $table, ':type' => $type ] )->one();

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
	public static function deleteByTable( $table, $config = [] ) {

		$siteId = isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

		$ptype = CoreGlobal::TYPE_SITE;

		return self::deleteAll( 'parentId=:pid AND parentType=:ptype AND name=:table', [ ':pid' => $siteId, ':ptype' =>  $ptype, ':table' => $table ] );
	}

}
