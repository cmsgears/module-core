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

/**
 * The Address model used to store the address fields, contact details and location.
 *
 * @property integer $id
 * @property integer $countryId
 * @property integer $provinceId
 * @property integer $regionId
 * @property integer $cityId
 * @property string $title
 * @property string $line1
 * @property string $line2
 * @property string $line3
 * @property string $countryName
 * @property string $provinceName
 * @property string $regionName
 * @property string $cityName
 * @property string $zip
 * @property string $subZip
 * @property string $firstName
 * @property string $lastName
 * @property string $phone
 * @property string $email
 * @property string $fax
 * @property string $website
 * @property string $landmark
 * @property integer $latitude
 * @property integer $longitude
 * @property integer $zoomLevel
 *
 * @since 1.0.0
 */
class Address extends \cmsgears\core\common\models\base\Resource {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	const TYPE_DEFAULT		= 'default';
	const TYPE_PRIMARY		= 'primary';
	const TYPE_RESIDENTIAL	= 'residential';
	const TYPE_SHIPPING		= 'shipping';
	const TYPE_BILLING		= 'billing';
	const TYPE_OFFICE		= 'office';	  // Office/ Registered
	const TYPE_MAILING		= 'mailing';   // Mailing/ Communication
	const TYPE_BRANCH		= 'branch';	  // Office having multiple branches

	// Public -----------------

	public static $typeList = [
		self::TYPE_DEFAULT,
		self::TYPE_PRIMARY,
		self::TYPE_RESIDENTIAL,
		self::TYPE_SHIPPING,
		self::TYPE_BILLING,
		self::TYPE_OFFICE,
		self::TYPE_MAILING,
		self::TYPE_BRANCH
	];

	public static $minTypeList = [
		self::TYPE_PRIMARY,
		self::TYPE_SHIPPING,
		self::TYPE_BILLING,
		self::TYPE_MAILING
	];

	public static $typeMap = [
		self::TYPE_DEFAULT => 'Default',
		self::TYPE_PRIMARY => 'Primary',
		self::TYPE_RESIDENTIAL => 'Residential',
		self::TYPE_SHIPPING => 'Shipping',
		self::TYPE_BILLING => 'Billing',
		self::TYPE_OFFICE => 'Office',
		self::TYPE_MAILING => 'Mailing',
		self::TYPE_BRANCH => 'Branch'
	];

	public static $officeTypeMap = [
		self::TYPE_SHIPPING => 'Shipping',
		self::TYPE_BILLING => 'Billing',
		self::TYPE_OFFICE => 'Office',
		self::TYPE_MAILING => 'Mailing',
		self::TYPE_BRANCH => 'Branch'
	];

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = CoreGlobal::TYPE_ADDRESS;

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
			[ [ 'countryId', 'provinceId', 'cityName', 'line1' ], 'required' ],
			[ [ 'email' ], 'required', 'on' => 'email' ],
			[ [ 'regionId' ], 'required', 'on' => 'region' ],
			[ [ 'zip' ], 'required', 'on' => 'postal' ],
			[ [ 'latitude', 'longitude' ], 'required', 'on' => 'location' ],
			[ [ 'regionId', 'zip' ], 'required', 'on' => 'regionpostal' ],
			[ [ 'regionId', 'latitude', 'longitude' ], 'required', 'on' => 'regionlocation' ],
			[ [ 'regionId', 'zip', 'latitude', 'longitude' ], 'required', 'on' => 'regionpostallocation' ],
			[ [ 'id' ], 'safe' ],
			// Text Limit
			[ [ 'zip', 'subZip' ], 'string', 'min' => 1, 'max' => Yii::$app->core->smallText ],
			[ [ 'phone', 'fax' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'countryName', 'provinceName', 'regionName' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ [ 'title', 'line1', 'line2', 'line3', 'cityName', 'firstName', 'lastName', 'email' ], 'string', 'min' => 0, 'max' => Yii::$app->core->xxLargeText ],
			[ [ 'website', 'landmark' ], 'string', 'min' => 0, 'max' => Yii::$app->core->xxxLargeText ],
			// Other
			[ [ 'zip', 'subZip' ], 'alphanumhyphenspace' ],
			[ 'regionId', 'number', 'integerOnly' => true, 'min' => 0 ],
			[ 'regionId', 'number', 'on' => [ 'region', 'regionpostal', 'regionlocation', 'regionpostallocation' ], 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
			[ [ 'countryId', 'provinceId', 'cityId' ], 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
			[ [ 'longitude', 'latitude', 'zoomLevel' ], 'number' ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [
					'line1', 'line2', 'line3', 'cityName', 'zip', 'subZip', 'firstName', 'lastName',
					'phone', 'email', 'fax', 'website', 'landmark', 'latitude', 'longitude'
				], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

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
			'line1' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LINE1 ),
			'line2' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LINE2 ),
			'line3' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LINE3 ),
			'countryName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_COUNTRY ),
			'provinceName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PROVINCE ),
			'regionName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_REGION ),
			'cityName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CITY ),
			'zip' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ZIP ),
			'subZip' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ZIP_SUB ),
			'firstName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FIRSTNAME ),
			'lastName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LASTNAME ),
			'phone' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PHONE ),
			'email' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_EMAIL ),
			'fax' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FAX ),
			'website' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_WEBSITE ),
			'landmark' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LANDMARK ),
			'latitude' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LATITUDE ),
			'longitude' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LONGITUDE ),
			'zoomLevel' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ZOOM )
		];
	}

	// yii\db\BaseActiveRecord

	/**
	 * @inheritdoc
	 */
	public function beforeSave( $insert ) {

		if( parent::beforeSave( $insert ) ) {

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

	// Address -------------------------------

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
	 * @return Region
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

	// TODO: Use address template to return the address string.
	public function toString() {

		$country	= !empty( $this->countryName ) ? $this->countryName : $this->country->name;
		$province	= !empty( $this->provinceName ) ? $this->provinceName : $this->province->name;
		$region		= !empty( $this->regionName ) ? $this->regionName : ( isset( $this->region ) ? $this->region->name : null );
		$address	= $this->line1;

		if( !empty( $this->line2 ) ) {

			$address .= ", $this->line2";
		}

		if( !empty( $this->line3 ) ) {

			$address .= ", $this->line3";
		}

		if( !empty( $this->cityName ) ) {

			$address .= ", $this->cityName";
		}

		if( !empty( $this->zip ) ) {

			$address .= ", $this->zip";
		}

		if( !empty( $region ) ) {

			$address .= ", $region";
		}

		$address .= ", $province, $country";

		return $address;
	}

	public function copyTo( $address ) {

		$this->copyForUpdateTo( $address, [
			'countryId', 'provinceId', 'cityId', 'title',
			'line1', 'line2', 'line3', 'cityName', 'provinceName', 'countryName',
			'zip', 'subZip', 'firstName', 'lastName', 'phone', 'email', 'fax', 'website',
			'longitude', 'latitude', 'zoomLevel'
		]);
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_ADDRESS );
	}

	// CMG parent classes --------------------

	// Address -------------------------------

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
	 * Return query to find the address with country.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with country.
	 */
	public static function queryWithCountry( $config = [] ) {

		$config[ 'relations' ] = [ 'country' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the address with province.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with province.
	 */
	public static function queryWithProvince( $config = [] ) {

		$config[ 'relations' ] = [ 'province' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the address with country and province.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with country and province.
	 */
	public static function queryWithCountryProvince( $config = [] ) {

		$config[ 'relations' ]	= [ 'country', 'province' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the address with region.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with region.
	 */
	public static function queryWithRegion( $config = [] ) {

		$config[ 'relations' ] = [ 'region' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
