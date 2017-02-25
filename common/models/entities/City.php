<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;

/**
 * City Entity
 *
 * @property long $id
 * @property long $countryId
 * @property long $provinceId
 * @property string $zone
 * @property string $name
 * @property string $postal
 * @property float $latitude
 * @property float $longitude
 */
class City extends \cmsgears\core\common\models\base\Entity {

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

		// model rules
		$rules = [
			// Required, Safe
			[ [ 'countryId', 'name' ], 'required' ],
			[ [ 'id' ], 'safe' ],
			// Unique
			[ [ 'countryId', 'provinceId', 'zone', 'name' ], 'unique', 'targetAttribute' => [ 'countryId', 'provinceId', 'zone', 'name' ] ],
			// Text Limit
			[ [ 'zone', 'name' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ 'postal', 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			// Other
			[ [ 'countryId', 'provinceId' ], 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
			[ [ 'latitude', 'longitude' ], 'number' ]
		];

		// trim if required
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'name', 'postal' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'countryId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_COUNTRY ),
			'provinceId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PROVINCE ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'postal' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ZIP ),
			'latitude' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LATITUDE ),
			'longitude' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LONGITUDE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// City ----------------------------------

	/**
	 * @return Country - parent country for province
	 */
	public function getCountry() {

		return $this->hasOne( Country::className(), [ 'id' => 'countryId' ] );
	}

	/**
	 * @return Province - parent province for city
	 */
	public function getProvince() {

		return $this->hasOne( Province::className(), [ 'id' => 'provinceId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::TABLE_CITY;
	}

	// CMG parent classes --------------------

	// City ----------------------------------

	// Read - Query -----------

	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'country', 'province' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	public static function queryWithCountry( $config = [] ) {

		$config[ 'relations' ]	= [ 'country' ];

		return parent::queryWithAll( $config );
	}

	public static function queryWithProvince( $config = [] ) {

		$config[ 'relations' ]	= [ 'province' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	/**
	 * @return array - by country id
	 */
	public static function findByCountryId( $countryId ) {

		return self::find()->where( 'countryId=:id', [ ':id' => $countryId ] )->all();
	}

	/**
	 * @return array - by province id
	 */
	public static function findByProvinceId( $provinceId ) {

		return self::find()->where( 'provinceId=:id', [ ':id' => $provinceId ] )->all();
	}

	/**
	 * @return Province - by country id and province id
	 */
	public static function findByCountryIdProvinceId( $countryId, $provinceId ) {

		return self::find()->where( 'countryId=:cid AND provinceId=:pid', [ ':cid' => $countryId, ':pid' => $provinceId ] )->all();
	}

	/**
	 * @return Province - by name, country id and province id
	 */
	public static function findByNameCountryIdProvinceId( $name, $countryId, $provinceId ) {

		return self::find()->where( 'name=:name AND countryId=:cid AND provinceId=:pid', [ ':name' => $name, ':cid' => $countryId, ':pid' => $provinceId ] )->one();
	}

	/**
	 * @return Province - check whether a province exist by the provided name and country id
	 */
	public static function isExistByNameCountryIdProvinceId( $name, $countryId, $provinceId ) {

		$city = self::findByNameCountryIdProvinceId( $name, $countryId, $provinceId );

		return isset( $city );
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
