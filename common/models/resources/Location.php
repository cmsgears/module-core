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
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\base\Resource;
use cmsgears\core\common\models\entities\Country;
use cmsgears\core\common\models\entities\Province;
use cmsgears\core\common\models\entities\City;

/**
 * The Location model used to store the location details.
 *
 * @property integer $id
 * @property integer $countryId
 * @property integer $provinceId
 * @property integer $regionId
 * @property integer $cityId
 * @property string $title
 * @property string $countryName
 * @property string $provinceName
 * @property string $regionName
 * @property string $cityName
 * @property string $zip
 * @property string $subZip
 * @property integer $latitude
 * @property integer $longitude
 * @property integer $zoomLevel
 *
 * @since 1.0.0
 */
class Location extends Resource {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = CoreGlobal::TYPE_LOCATION;

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
			[ 'id', 'safe' ],
			[ [ 'latitude', 'longitude' ], 'required', 'on' => 'location' ],
			// Text Limit
			[ [ 'zip', 'subZip' ], 'string', 'min' => 1, 'max' => Yii::$app->core->smallText ],
			[ [ 'countryName', 'provinceName', 'regionName' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ [ 'title', 'cityName' ], 'string', 'min' => 0, 'max' => Yii::$app->core->xxLargeText ],
			// Other
			[ [ 'zip', 'subZip' ], 'alphanumhyphenspace' ],
			[ [ 'countryId', 'provinceId', 'cityId' ], 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
			[ [ 'longitude', 'latitude', 'zoomLevel' ], 'number' ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'title', 'zip', 'subZip', 'latitude', 'longitude' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

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
			'provinceId' => Yii::$app->core->provinceLabel,
			'regionId' => Yii::$app->core->regionLabel,
			'cityId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CITY ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'countryName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_COUNTRY ),
			'provinceName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PROVINCE ),
			'regionName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_REGION ),
			'cityName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CITY ),
			'zip' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ZIP ),
			'subZip' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ZIP_SUB ),
			'latitude' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LATITUDE ),
			'longitude' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LONGITUDE ),
			'zoomLevel' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ZOOM )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Location  -----------------------------

	/**
	 * @return Country
	 */
	public function getCountry() {

		return $this->hasOne( Country::class, [ 'id' => 'countryId' ] );
	}

	/**
	 * @return Province
	 */
	public function getProvince() {

		return $this->hasOne( Province::class, [ 'id' => 'provinceId' ] );
	}

	/**
	 * @return City
	 */
	public function getCity() {

		return $this->hasOne( City::class, [ 'id' => 'cityId' ] );
	}

	// TODO: Use location template to return the location string.
	public function toString() {

		$countryName	= !empty( $this->countryName ) ? $this->countryName : ( isset( $this->country ) ? $this->country->name : null );
		$provinceName	= !empty( $this->provinceName ) ? $this->provinceName : ( isset( $this->province ) ? $this->province->name : null );
		$cityName		= !empty( $this->cityName ) ? $this->cityName : ( isset( $this->city ) ? $this->city->name : null );

		$location = $this->title;

		if( isset( $cityName ) && strlen( $cityName ) > 0 ) {

			$location .= ", $cityName";
		}

		if( isset( $provinceName ) && strlen( $provinceName ) > 0 ) {

			$location .= ", $provinceName";
		}

		if( isset( $provinceName ) && isset( $countryName ) && strlen( $countryName ) > 0 ) {

			$location .= ", $countryName";
		}

		return $location;
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_LOCATION );
	}

	// CMG parent classes --------------------

	// Location  -----------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'country', 'province', 'city' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the location with country.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with country.
	 */
	public static function queryWithCountry( $config = [] ) {

		$config[ 'relations' ] = [ 'country' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the location with province.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with province.
	 */
	public static function queryWithProvince( $config = [] ) {

		$config[ 'relations' ] = [ 'province' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the location with country and province.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with country and province.
	 */
	public static function queryWithCity( $config = [] ) {

		$config[ 'relations' ] = [ 'city' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
