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

use cmsgears\core\common\models\traits\base\NameTrait;

/**
 * City Entity
 *
 * @property integer $id
 * @property integer $countryId
 * @property integer $provinceId
 * @property integer $regionId
 * @property string $name
 * @property string $title
 * @property string $code
 * @property string $iso
 * @property string $type
 * @property string $postal
 * @property string $zone
 * @property string $regions
 * @property string $zipCodes
 * @property integer $timeZone
 * @property float $latitude
 * @property float $longitude
 * @property string $autoCache
 *
 * @since 1.0.0
 */
class City extends \cmsgears\core\common\models\base\Entity implements IName {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Low level divisions
	const TYPE_DEFAULT	= CoreGlobal::TYPE_DEFAULT;
	const TYPE_CITY		= 'city';
	const TYPE_TOWN		= 'town';
	const TYPE_VILLAGE	= 'village';

	// Group level divisions
	const TYPE_COUNTY		= 'county';
	const TYPE_MUNICIPALITY	= 'municipality';
	const TYPE_TEHSIL		= 'tehsil';
	const TYPE_TALUKA		= 'taluka';
	const TYPE_MANDAL		= 'mandal';

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = CoreGlobal::TYPE_CITY;

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
			[ [ 'countryId', 'name' ], 'required' ],
			[ [ 'id' ], 'safe' ],
			// Unique
			//[ [ 'zone', 'iso' ], 'unique', 'targetAttribute' => [ 'countryId', 'provinceId', 'name', 'zone', 'iso' ] ],
			// Text Limit
			[ 'code', 'string', 'min' => 1, 'max' => Yii::$app->core->smallText ],
			[ [ 'type', 'postal' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'name', 'title', 'iso', 'zone' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			[ [ 'regions', 'zipCodes', 'autoCache' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ 'timeZone', 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'countryId', 'provinceId', 'regionId' ], 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
			[ [ 'latitude', 'longitude' ], 'number' ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'name', 'title', 'code', 'iso', 'postal', 'zone' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

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
			'regionId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_REGION ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'code' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CODE ),
			'iso' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ISO ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'postal' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ZIP ),
			'zone' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ZONE ),
			'regions' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_REGIONS ),
			'zipCodes' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ZIP_CODES ),
			'timeZone' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TIME_ZONE ),
			'latitude' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LATITUDE ),
			'longitude' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LONGITUDE )
		];
	}

	// yii\db\BaseActiveRecord

	/**
	 * @inheritdoc
	 */
	public function beforeSave( $insert ) {

		if( parent::beforeSave( $insert ) ) {

			if( $this->timeZone <= 0 ) {

				$this->timeZone = null;
			}

			$auto = $this->name;

			if( isset( $this->region ) ) {

				$auto = "$auto, {$this->region->name}";
			}

			if( isset( $this->province ) ) {

				$this->autoCache = "$auto, {$this->province->name}";
			}

			// Default Type - Default
			$this->type = $this->type ?? CoreGlobal::TYPE_DEFAULT;

			return true;
		}

		return false;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// City ----------------------------------

	/**
	 * Return corresponding country to which this city belongs.
	 *
	 * @return Country
	 */
	public function getCountry() {

		return $this->hasOne( Country::class, [ 'id' => 'countryId' ] );
	}

	/**
	 * Return corresponding province to which this city belongs.
	 *
	 * @return Province
	 */
	public function getProvince() {

		return $this->hasOne( Province::class, [ 'id' => 'provinceId' ] );
	}

	/**
	 * Return corresponding region to which this city belongs.
	 *
	 * @return Province
	 */
	public function getRegion() {

		return $this->hasOne( Region::class, [ 'id' => 'regionId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_CITY );
	}

	// CMG parent classes --------------------

	// City ----------------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'country', 'province' ];

		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the city with country.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with country.
	 */
	public static function queryWithCountry( $config = [] ) {

		$config[ 'relations' ] = [ 'country' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the city with province.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with province.
	 */
	public static function queryWithProvince( $config = [] ) {

		$config[ 'relations' ] = [ 'province' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the city with region.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with province.
	 */
	public static function queryWithRegion( $config = [] ) {

		$config[ 'relations' ] = [ 'region' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the city using country id.
	 *
	 * @param integer $countryId
	 * @return \yii\db\ActiveQuery to query by country id.
	 */
	public static function queryByCountryId( $countryId ) {

		return self::find()->where( 'countryId=:id', [ ':id' => $countryId ] );
	}

	/**
	 * Return query to find the city using province id.
	 *
	 * @param integer $provinceId
	 * @return \yii\db\ActiveQuery to query by province id.
	 */
	public static function queryByProvinceId( $provinceId ) {

		return self::find()->where( 'provinceId=:id', [ ':id' => $provinceId ] );
	}

	/**
	 * Return query to find the city using country id and province id.
	 *
	 * @param integer $countryId
	 * @param integer $provinceId
	 * @return \yii\db\ActiveQuery to query by country id and province id.
	 */
	public static function queryByCountryIdProvinceId( $countryId, $provinceId ) {

		return self::find()->where( 'countryId=:cid AND provinceId=:pid', [ ':cid' => $countryId, ':pid' => $provinceId ] );
	}

	/**
	 * Return query to find the city using region id.
	 *
	 * @param integer $regionId
	 * @return \yii\db\ActiveQuery to query by region id.
	 */
	public static function queryByRegionId( $regionId ) {

		return self::find()->where( 'regionId=:id', [ ':id' => $regionId ] );
	}

	/**
	 * Return query to find the city using country id, province id, and region id.
	 *
	 * @param integer $countryId
	 * @param integer $provinceId
	 * @param integer $regionId
	 * @return \yii\db\ActiveQuery to query by country id, province id, and region id.
	 */
	public static function queryByCountryIdProvinceIdRegionId( $countryId, $provinceId, $regionId ) {

		return self::find()->where( 'countryId=:cid AND provinceId=:pid AND regionId=:rid', [ ':cid' => $countryId, ':pid' => $provinceId, ':rid' => $regionId ] );
	}

	// Read - Find ------------

	/**
	 * Try to find out a city having unique name within province.
	 *
	 * @param string $name
	 * @param integer $countryId
	 * @param integer $provinceId
	 * @return City by name, country id and province id
	 */
	public static function findUnique( $name, $countryId, $provinceId ) {

		return self::find()->where( 'name=:name AND countryId=:cid AND provinceId=:pid', [ ':name' => $name, ':cid' => $countryId, ':pid' => $provinceId ] )->one();
	}

	/**
	 * Try to find out a city having unique name within zone.
	 *
	 * @param string $name
	 * @param integer $countryId
	 * @param integer $provinceId
	 * @param string $zone
	 * @return City by name, country id, province id and zone
	 */
	public static function findUniqueByZone( $name, $countryId, $provinceId, $zone ) {

		return self::find()->where( 'name=:name AND countryId=:cid AND provinceId=:pid AND zone=:zone', [ ':name' => $name, ':cid' => $countryId, ':pid' => $provinceId, ':zone' => $zone ] )->one();
	}

	/**
	 * Try to find out a city having unique name within region.
	 *
	 * @param string $name
	 * @param integer $countryId
	 * @param integer $provinceId
	 * @param integer $regionId
	 * @return City by name, country id, province id and region id
	 */
	public static function findUniqueByRegionId( $name, $countryId, $provinceId, $regionId ) {

		return self::find()->where( 'name=:name AND countryId=:cid AND provinceId=:pid AND regionId=:rid', [ ':name' => $name, ':cid' => $countryId, ':pid' => $provinceId, ':rid' => $regionId ] )->one();
	}

	/**
	 * Check whether a city already exist using given name within province.
	 *
	 * @param string $name
	 * @param integer $countryId
	 * @param integer $provinceId
	 * @return boolean
	 */
	public static function isUniqueExist( $name, $countryId, $provinceId ) {

		$city = self::findUnique( $name, $countryId, $provinceId );

		return isset( $city );
	}

	/**
	 * Check whether a city already exist using given name within zone.
	 *
	 * @param string $name
	 * @param integer $countryId
	 * @param integer $provinceId
	 * @return boolean
	 */
	public static function isUniqueExistByZone( $name, $countryId, $provinceId, $zone ) {

		$city = self::findUniqueByZone( $name, $countryId, $provinceId, $zone );

		return isset( $city );
	}

	/**
	 * Check whether a city already exist using given name within region.
	 *
	 * @param string $name
	 * @param integer $countryId
	 * @param integer $provinceId
	 * @param integer $regionId
	 * @return boolean
	 */
	public static function isUniqueExistByRegionId( $name, $countryId, $provinceId, $regionId ) {

		$city = self::findUniqueByRegionId( $name, $countryId, $provinceId, $regionId );

		return isset( $city );
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
