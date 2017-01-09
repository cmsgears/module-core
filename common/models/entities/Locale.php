<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\models\traits\NameTrait;

/**
 * Locale Entity
 *
 * @property long $id
 * @property string $code
 * @property string $name
 */
class Locale extends \cmsgears\core\common\models\base\Entity {

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

	public function rules() {

		// model rules
		$rules = [
			// Required, Safe
			[ [ 'code', 'name' ], 'required' ],
			[ 'id', 'safe' ],
			// Unique
			[ [ 'code' ], 'unique' ],
			[ [ 'name' ], 'unique' ],
			// Text Limit
			[ 'code', 'string', 'min' => 1, 'max' => Yii::$app->core->smallText ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ]
		];

		// trim if required
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

		return CoreTables::TABLE_LOCALE;
	}

	// CMG parent classes --------------------

	// Locale --------------------------------

	// Read - Query -----------

	// Read - Find ------------

	/**
	 * @return Locale - by code
	 */
	public static function findByCode( $code ) {

		return self::find()->where( 'code=:code', [ ':code' => $code ] )->one();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------
}