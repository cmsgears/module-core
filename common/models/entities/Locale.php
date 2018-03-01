<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\entities;

// Yii Imports
use Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\interfaces\base\IName;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\base\Entity;

use cmsgears\core\common\models\traits\base\NameTrait;

/**
 * Represents specific language and region.
 *
 * Example:
 * Code - en-US
 * Name - English, United States
 *
 * The above mentioned code and name represent English Language preferred in United States.
 *
 * @property long $id
 * @property string $code
 * @property string $name
 *
 * @since 1.0.0
 */
class Locale extends Entity implements IName {

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

	use NameTrait;

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
			[ [ 'code', 'name' ], 'required' ],
			[ 'id', 'safe' ],
			// Unique
			[ 'code', 'unique' ],
			[ 'name', 'unique' ],
			// Text Limit
			[ 'code', 'string', 'min' => 1, 'max' => Yii::$app->core->smallText ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'name', 'code' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'code' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CODE ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Locale --------------------------------

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_LOCALE );
	}

	// CMG parent classes --------------------

	// Locale --------------------------------

	// Read - Query -----------

	// Read - Find ------------

	/**
	 * Find and return the locale associated with given code.
	 *
	 * @param string $code
	 * @return Locale
	 */
	public static function findByCode( $code ) {

		return self::find()->where( 'code=:code', [ ':code' => $code ] )->one();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
