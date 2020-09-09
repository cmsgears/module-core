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
use cmsgears\core\common\models\entities\Country;
use cmsgears\core\common\models\entities\Province;
use cmsgears\core\common\models\entities\Region;
use cmsgears\core\common\models\entities\City;

use cmsgears\core\common\models\interfaces\resources\IData;

use cmsgears\core\common\models\traits\resources\DataTrait;

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
 * @property datetime $accessedAt
 * @property string $notes
 * @property string $data
 *
 * @since 1.0.0
 */
class Location extends \cmsgears\core\common\models\base\Resource implements IData {

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

	use DataTrait;

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
			[ [ 'id', 'notes' ], 'safe' ],
			[ [ 'latitude', 'longitude' ], 'required', 'on' => 'location' ],
			// Text Limit
			[ [ 'zip', 'subZip' ], 'string', 'min' => 1, 'max' => Yii::$app->core->smallText ],
			[ [ 'countryName', 'provinceName', 'regionName' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ [ 'title', 'cityName' ], 'string', 'min' => 0, 'max' => Yii::$app->core->xxLargeText ],
			// Other
			[ [ 'zip', 'subZip' ], 'alphanumhyphenspace' ],
			[ [ 'countryId', 'provinceId', 'regionId', 'cityId' ], 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
			[ [ 'longitude', 'latitude', 'zoomLevel' ], 'number' ],
			[ 'accessedAt', 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
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
			'zoomLevel' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ZOOM ),
			'notes' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NOTES )
		];
	}

	// yii\db\BaseActiveRecord

	/**
	 * @inheritdoc
	 */
	public function beforeSave( $insert ) {

		if( parent::beforeSave( $insert ) ) {

			if( $this->countryId <= 0 ) {

				$this->countryId = null;
			}

			if( $this->provinceId <= 0 ) {

				$this->provinceId = null;
			}

			if( $this->regionId <= 0 ) {

				$this->regionId = null;
			}

			if( $this->cityId <= 0 ) {

				$this->cityId = null;
			}

			return true;
		}

		return false;
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
	 * @return Province
	 */
	public function getRegion() {

		return $this->hasOne( Region::class, [ 'id' => 'regionId' ] );
	}

	/**
	 * @return City
	 */
	public function getCity() {

		return $this->hasOne( City::class, [ 'id' => 'cityId' ] );
	}

	// TODO: Use location template to return the location string.
	public function toString() {

		$country	= !empty( $this->countryName ) ? $this->countryName : ( isset( $this->country ) ? $this->country->name : null );
		$province	= !empty( $this->provinceName ) ? $this->provinceName : ( isset( $this->province ) ? $this->province->name : null );
		$region		= !empty( $this->regionName ) ? $this->regionName : ( isset( $this->region ) ? $this->region->name : null );
		$city		= !empty( $this->cityName ) ? $this->cityName : ( isset( $this->city ) ? $this->city->name : null );

		$location = $this->title;

		if( !empty( $city ) ) {

			$location .= ", $city";
		}

		if( !empty( $region) ) {

			$location .= ", $region";
		}

		if( !empty( $province ) ) {

			$location .= ", $province";
		}

		if( !empty( $country ) ) {

			$location .= ", $country";
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

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'country', 'province', 'region', 'city' ];

		$config[ 'relations' ] = $relations;

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
	 * Return query to find the location with region.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with region.
	 */
	public static function queryWithRegion( $config = [] ) {

		$config[ 'relations' ] = [ 'region' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the location with city.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with city.
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
