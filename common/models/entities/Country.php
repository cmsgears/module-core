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
 * Country Entity
 *
 * @property long $id
 * @property string $code
 * @property string $iso
 * @property string $name
 */
class Country extends \cmsgears\core\common\models\base\Entity {

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

		// model rules
		$rules = [
			// Required, Safe
			[ [ 'name', 'code' ], 'required' ],
			[ 'id', 'safe' ],
			// Unique
			[ [ 'code' ], 'unique' ],
			[ [ 'name' ], 'unique'],
			// Text Limit
			[ [ 'code', 'iso' ], 'string', 'min' => 1, 'max' => Yii::$app->core->smallText ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ]
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
			'iso' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ISO ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Country -------------------------------

	/**
	 * @return array - list of Province having all the provinces belonging to this country
	 */
	public function getProvinces() {

		return $this->hasMany( Province::className(), [ 'countryId' => 'id' ] );
	}

	public function getCities() {

		return $this->hasMany( City::className(), [ 'countryId' => 'id' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::TABLE_COUNTRY;
	}

	// CMG parent classes --------------------

	// Country -------------------------------

	// Read - Query -----------

	public static function queryWithProvinces( $config = [] ) {

		$config[ 'relations' ]	= [ 'provinces' ];

		return parent::queryWithAll( $config );
	}

	public static function queryWithCities( $config = [] ) {

		$config[ 'relations' ]	= [ 'cities' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	/**
	 * @return Country by code
	 */
	public static function findByCode( $code ) {

		return self::find()->where( 'code=:code', [ ':code' => $code ] )->one();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
