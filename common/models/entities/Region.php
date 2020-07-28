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
 * Region Entity
 *
 * @property integer $id
 * @property integer $countryId
 * @property integer $provinceId
 * @property string $code
 * @property string $iso
 * @property string $name
 * @property string $title
 *
 * @since 1.0.0
 */
class Region extends \cmsgears\core\common\models\base\Entity implements IName {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = CoreGlobal::TYPE_REGION;

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
			[ [ 'countryId', 'provinceId', 'name' ], 'required' ],
			[ [ 'id' ], 'safe' ],
			// Unique
			[ [ 'code' ], 'unique', 'targetAttribute' => [ 'countryId', 'provinceId', 'code' ], 'comboNotUnique' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_EXIST ) ],
			[ [ 'iso' ], 'unique', 'targetAttribute' => [ 'countryId', 'provinceId', 'iso' ], 'comboNotUnique' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_EXIST ) ],
			[ [ 'name' ], 'unique', 'targetAttribute' => [ 'countryId', 'provinceId', 'name' ], 'comboNotUnique' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_EXIST ) ],
			// Text Limit
			[ [ 'code', 'iso' ], 'string', 'min' => 1, 'max' => Yii::$app->core->smallText ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ 'title', 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			// Other
			[ [ 'countryId', 'provinceId' ], 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'code', 'iso', 'name', 'title' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

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
			'code' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CODE ),
			'iso' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ISO ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Region --------------------------------

	/**
	 * Return corresponding country to which this region belongs.
	 *
	 * @return Country
	 */
	public function getCountry() {

		return $this->hasOne( Country::class, [ 'id' => 'countryId' ] );
	}

	/**
	 * Return corresponding province to which this region belongs.
	 *
	 * @return Province
	 */
	public function getProvince() {

		return $this->hasOne( Province::class, [ 'id' => 'provinceId' ] );
	}

	/**
	 * Return list of cities belonging to this region.
	 *
	 * @return City[]
	 */
	public function getCities() {

		return $this->hasMany( City::class, [ 'regionId' => 'id' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_REGION );
	}

	// CMG parent classes --------------------

	// Region --------------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'country', 'province' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the region with country.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with country.
	 */
	public static function queryWithCountry( $config = [] ) {

		$config[ 'relations' ] = [ 'country' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the region with province.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with province.
	 */
	public static function queryWithProvince( $config = [] ) {

		$config[ 'relations' ] = [ 'province' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the region using country id.
	 *
	 * @param integer $countryId
	 * @return \yii\db\ActiveQuery to query by country id.
	 */
	public static function queryByCountryId( $countryId ) {

		return self::find()->where( 'countryId=:id', [ ':id' => $countryId ] );
	}

	/**
	 * Return query to find the region using province id.
	 *
	 * @param integer $provinceId
	 * @return \yii\db\ActiveQuery to query by province id.
	 */
	public static function queryByProvinceId( $provinceId ) {

		return self::find()->where( 'provinceId=:id', [ ':id' => $provinceId ] );
	}

	/**
	 * Return query to find the region using country id and province id.
	 *
	 * @param integer $countryId
	 * @param integer $provinceId
	 * @return \yii\db\ActiveQuery to query by country id and province id.
	 */
	public static function queryByCountryIdProvinceId( $countryId, $provinceId ) {

		return self::find()->where( 'countryId=:cid AND provinceId=:pid', [ ':cid' => $countryId, ':pid' => $provinceId ] );
	}

	// Read - Find ------------

	/**
	 * Find and return the regions associated with given country id.
	 *
	 * @param integer $countryId
	 * @return Region[]
	 */
	public static function findByCountryId( $countryId ) {

		return self::find()->where( 'countryId=:id', [ ':id' => $countryId ] )->all();
	}

	/**
	 * Find and return the region associated with given country id and iso.
	 *
	 * @param integer $countryId
	 * @param string $iso
	 * @return Region
	 */
	public static function findByCountryIdIso( $countryId, $iso ) {

		return self::find()->where( 'countryId=:id AND iso=:iso', [ ':id' => $countryId, ':iso' => $iso ] )->one();
	}

	/**
	 * Find and return the regions associated with given country id.
	 *
	 * @param integer $countryId
	 * @return Region[]
	 */
	public static function findByCountryIdProvinceId( $countryId, $provinceId ) {

		return self::find()->where( 'countryId=:id AND provinceId=:pid', [ ':id' => $countryId, ':pid' => $provinceId ] )->all();
	}

	/**
	 * Try to find out a region having unique name within province.
	 *
	 * @param string $name
	 * @param integer $countryId
	 * @param integer $provinceId
	 * @return Region by name, country id and province id
	 */
	public static function findUnique( $name, $countryId, $provinceId ) {

		return self::find()->where( 'name=:name AND countryId=:cid AND provinceId=:pid', [ ':name' => $name, ':cid' => $countryId, ':pid' => $provinceId ] )->one();
	}

	/**
	 * Check whether a region already exist using given name within province.
	 *
	 * @param string $name
	 * @param integer $countryId
	 * @param integer $provinceId
	 * @return boolean
	 */
	public static function isUniqueExist( $name, $countryId, $provinceId ) {

		$region = self::findUnique( $name, $countryId, $provinceId );

		return isset( $region );
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
