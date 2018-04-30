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
 * The Address model used to store the address fields, contact details and location.
 *
 * @property integer $id
 * @property integer $countryId
 * @property integer $provinceId
 * @property integer $cityId
 * @property string $title
 * @property string $line1
 * @property string $line2
 * @property string $line3
 * @property string $countryName
 * @property string $provinceName
 * @property string $cityName
 * @property string $zip
 * @property string $subZip
 * @property string $firstName
 * @property string $lastName
 * @property string $phone
 * @property string $email
 * @property string $fax
 * @property string $website
 * @property integer $latitude
 * @property integer $longitude
 * @property integer $zoomLevel
 *
 * @since 1.0.0
 */
class Address extends Resource {

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

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType	= CoreGlobal::TYPE_ADDRESS;

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
			[ [ 'countryId', 'provinceId', 'cityName', 'line1', 'zip' ], 'required' ],
			[ [ 'latitude', 'longitude' ], 'required', 'on' => 'location' ],
			[ [ 'id' ], 'safe' ],
			// Text Limit
			[ [ 'zip', 'subZip' ], 'string', 'min' => 1, 'max' => Yii::$app->core->smallText ],
			[ [ 'phone', 'fax' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'countryName', 'provinceName' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ [ 'title', 'line1', 'line2', 'line3', 'cityName', 'firstName', 'lastName', 'email' ], 'string', 'min' => 0, 'max' => Yii::$app->core->xxLargeText ],
			[ 'website', 'string', 'min' => 0, 'max' => Yii::$app->core->xxxLargeText ],
			// Other
			[ [ 'zip', 'subZip' ], 'alphanumhyphenspace' ],
			[ [ 'countryId', 'provinceId', 'cityId' ], 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
			[ [ 'longitude', 'latitude', 'zoomLevel' ], 'number' ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'line1', 'line2', 'line3', 'cityName', 'zip', 'subZip', 'firstName', 'lastName', 'phone', 'email', 'fax', 'website', 'latitude', 'longitude' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

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
			'cityId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CITY ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'line1' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LINE1 ),
			'line2' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LINE2 ),
			'line3' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LINE3 ),
			'countryName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_COUNTRY ),
			'provinceName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PROVINCE ),
			'cityName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CITY ),
			'zip' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ZIP ),
			'subZip' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ZIP_SUB ),
			'firstName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FIRSTNAME ),
			'lastName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LASTNAME ),
			'phone' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PHONE ),
			'email' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_EMAIL ),
			'fax' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FAX ),
			'website' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_WEBSITE ),
			'latitude' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LATITUDE ),
			'longitude' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LONGITUDE ),
			'zoomLevel' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ZOOM )
		];
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
	 * @return City
	 */
	public function getCity() {

		return $this->hasOne( City::class, [ 'id' => 'cityId' ] );
	}

	// TODO: Use address template to return the address string.
	public function toString() {

		$country	= isset( $this->countryName ) ? $this->countryName : $this->country->name;
		$province	= isset( $this->provinceName ) ? $this->provinceName : $this->province->name;
		$address	= $this->line1;

		if( isset( $this->line2 ) && strlen( $this->line2 ) > 0 ) {

			$address .= ", $this->line2";
		}

		if( isset( $this->line3 ) && strlen( $this->line3 ) > 0 ) {

			$address .= ", $this->line3";
		}

		if( isset( $this->cityName ) && strlen( $this->cityName ) > 0 ) {

			$address .= ", $this->cityName";
		}

		$address .= "$this->zip, $country, $province";
		//$address .= ", $this->zip";

		return $address;
	}

	public function copyTo( $address ) {

		$this->copyForUpdateTo( $address, [ 'countryId', 'provinceId', 'cityId', 'title', 'line1', 'line2', 'line3', 'cityName', 'provinceName', 'countryName', 'zip', 'subZip', 'firstName', 'lastName', 'phone', 'email', 'fax', 'website', 'longitude', 'latitude', 'zoomLevel' ] );
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

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'country', 'province', 'city' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the address with country.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with country.
	 */
	public static function queryWithCountry( $config = [] ) {

		$config[ 'relations' ]	= [ 'country' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the address with province.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with province.
	 */
	public static function queryWithProvince( $config = [] ) {

		$config[ 'relations' ]	= [ 'province' ];

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

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
